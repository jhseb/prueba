<?php

namespace App\Controller;

use App\Entity\Estudiante;
use App\Entity\User;
use App\Form\EstudianteFormType;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EstudianteController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    // Mostrar registros
    #[Route('/', name: 'crud_show')]
    public function show(): Response
    {
        $users = $this->em->getRepository(Estudiante::class)->findAll();

        return $this->render('home/show.html.twig', [
            'users' => $users,
        ]);
    }

    // Agregar registro
    #[Route('/crud/add', name: 'crud_add')]
    public function add(Request $request): Response
    {
        $user = new Estudiante();
        $form = $this->createForm(EstudianteFormType::class, $user, [
            'is_edit' => false,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $campos = $form->getData();

                $user->setIdentidad($campos->getIdentidad());
                $user->setNombre($campos->getNombre());
                $user->setSalon($campos->getSalon());
                $user->setAcudiente($campos->getAcudiente());
                $user->setEdad($campos->getEdad());
                $user->setGenero($campos->getGenero());

                $this->em->persist($user);
                $this->em->flush();

                flash()->success('Estudiante registrado correctamente.');
                return $this->redirectToRoute('crud_show');
            } catch (\Exception $e) {
                flash()->error('Ocurrió un error al guardar el estudiante.');
                return $this->redirectToRoute('crud_add');
            }
        }

        return $this->render('home/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Actualizar registro
    #[Route('/crud/update/{id}', name: 'crud_update')]
    public function update(int $id, Request $request): Response
    {
        $user = $this->em->getRepository(Estudiante::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No se encontró el registro con el id: ' . $id
            );
        }

        $form = $this->createForm(EstudianteFormType::class, $user, [
            'is_edit' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->em->flush();

                flash()->success('Estudiante actualizado correctamente.');
                return $this->redirectToRoute('crud_show');
            } catch (\Exception $e) {
                flash()->error('Ocurrió un error al actualizar el estudiante.');
                return $this->redirectToRoute('crud_update', ['id' => $id]);
            }
        }

        return $this->render('home/update.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    // Eliminar registro
    #[Route('/crud/delete/{id}', name: 'crud_delete')]
    public function delete(int $id): Response
    {
        $user = $this->em->getRepository(Estudiante::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No se encontró el registro con el id: ' . $id
            );
        }

        $this->em->remove($user);
        $this->em->flush();

        flash()->success('Estudiante eliminado correctamente.');
        return $this->redirectToRoute('crud_show');
    }
}
