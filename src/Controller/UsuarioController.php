<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Usuario;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UsuarioController extends AbstractController
{
    /**
     * @Route("/", name="inicio")
     */
    public function index(): Response
    {
        return $this->render('usuario/index.html.twig', [
            'controller_name' => 'UsuarioController',
        ]);
    }
    
    /**
     * @Route("/registrar", name="registrar")
     */
    public function registrar(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $usuario = new Usuario();
        $form = $this->createFormBuilder($usuario)
                ->add('email', TextType::class)
                ->add('password', PasswordType::class,['attr' => ['minlength' => 4]])
                ->add('nombre', TextType::class)
                ->add('apellidos', TextType::class)
                ->add('telefono',IntegerType::class)
                ->add('pais', TextType::class)
                 ->add('sexo', ChoiceType::class,[
                    'choices' => [
                        'Masculino'=>'Masculino',
                        'Femenino'=>'Femenino',
                        'Otro'=>'otro'
                      
                    ],
                ])
                
                ->add('imagen', FileType::class, [
                    'label' => 'Selecciona imagen',
                    'constraints' => [
                        new File([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                                'image/gif'
                            ],
                            'mimeTypesMessage' => 'Formato de archivo no válido',
                                ])
                    ]
                ])
               
                
       
                
                ->add('registrar', SubmitType::class, ['label' => 'Registrar'])
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $usuario = $form->getData();
            $foto = $form->get('imagen')->getData();
            
            if ($foto) {
                
                $nuevo_nombre = uniqid() . '.' . $foto->guessExtension();

                try {
                    $foto->move('imagenes/',$nuevo_nombre);
                    $usuario->setImagen($nuevo_nombre);
                } catch (FileException $e) {
                    
                }
            }

            //Codificamos el password
            $usuario->setPassword($encoder->encodePassword($usuario, $usuario->getPassword()));
            
            //Guardamos el nuevo usuario en la base de datos
            $em = $this->getDoctrine()->getManager();
            $em->persist($usuario);
            $em->flush();             
            $this->addFlash(
                    'notice',
                    'Se ha credo correctamente'
                    );

            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('usuario/registrar.html.twig',
                        ['form' => $form->createView()]);
    }
    
     /**
     * @Route("/usuarios/{id}", name="perfil", requirements={"id"="\d+"})
     * @param int $id
     */
    public function ver(Usuario $usuario) {
  
        return $this->render('usuario/perfil.html.twig',
                        ['usuario' => $usuario]);
    }
}
