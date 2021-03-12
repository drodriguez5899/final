<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller;
use App\Entity\Mensajes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Usuario;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



/**
 * Description of MensajeController
 *
 * @author David
 */
class MensajeController extends AbstractController {
    /**
     * @Route("/mensaje", name="foro")
     */
    public function index(): Response {
        $repositorio = $this->getDoctrine()->getRepository(Mensajes::class);
        $mensaje = $repositorio->findAll();
        return $this->render('mensaje/index.html.twig',
                        ['mensaje' => $mensaje]);
    }
    
    
    
      /**
     * @Route("/mensaje/insertar", name="insertar_mensaje")
     */
    public function insertar(Request $request): Response {
        $mensaje = new Mensajes();
        $form = $this->createFormBuilder($mensaje)
                ->add('titulo', TextType::class)
                ->add('contenido', TextareaType::class)
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
               
                
                ->add('enviar', SubmitType::class, ['label' => 'Insertar mensaje'])
                ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mensaje = $form->getData();
            $foto = $form->get('imagen')->getData();
            
            if ($foto) {
                
                $nuevo_nombre = uniqid() . '.' . $foto->guessExtension();

                try {
                    $foto->move('imagenes/',$nuevo_nombre);
                    $mensaje->setImagen($nuevo_nombre);
                } catch (FileException $e) {
                    
                }
            }

            //Guardamos el nuevo cliente en la base de datos
            $em = $this->getDoctrine()->getManager();
            $mensaje ->setUsuario($this->getUser());
            $em->persist($mensaje);
            $em->flush();             
            $this->addFlash(
                    'notice',
                    'Se ha creado correctamente'
                    );

            return $this->redirectToRoute('foro');
        }

        return $this->render('mensaje/insertar_mensaje.html.twig',
                        ['form' => $form->createView()]);





        return $this->redirectToRoute('foro');
    }
    
     /**
     * @Route ("/mensaje/borrar/{id}",name="borrar_mensaje")
     * @return Response
     */
    public function borrar(Mensajes $mensaje): Response {
        $em = $this->getDoctrine()->getManager();
        $em->remove($mensaje);
        $em->flush();
        $this->addFlash(
                    'notice',
                    'Se ha borrado correctamente'
                    );
        return $this->redirectToRoute('foro');
    }
    
    
     
   /**
     * @Route("/mensaje/{id}", name="ver_mensaje", requirements={"id"="\d+"})
     * @param int $id
     */
    public function ver(Mensajes $mensaje): Response {
        return $this->render('mensaje/ver_mensaje.html.twig',
                        ['mensaje' => $mensaje]);
    }
    
      /**
     * @Route("/mensaje/editar/{id}", name="editar_mensaje")
     * Method({"GET", "POST"})
     */
    public function editar(Request $request, $id) {
      $mensaje = new Mensajes();
      $mensaje = $this->getDoctrine()->getRepository(Mensajes::class)->find($id);

      $form = $this->createFormBuilder($mensaje)
        ->add('titulo', TextType::class)
        ->add('contenido', TextareaType::class)
              
         
        ->add('editar', SubmitType::class, array(
          'label' => 'Update',
          'attr' => array('class' => 'btn btn-primary mt-3')
        ))
        ->getForm();

      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()) {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();
        $this->addFlash(
                    'notice',
                    'Se ha modificado correctamente'
                    );

        return $this->redirectToRoute('foro');
      }

      return $this->render('mensaje/editar_mensaje.html.twig', array(
        'form' => $form->createView()
      ));
    }
     
    
    
    //put your code here
}
