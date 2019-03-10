<?php

namespace App\Controller;


use App\Form\Type\SearchRestaurantType;
use App\Restaurant\RestaurantDate;
use App\Repository\RestaurantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RestaurantController extends Controller
{
    public function listing(Request $request, RestaurantRepository $restaurantRepository)
    {
        $searchTerm = $request->query->get('s', '');
        $closed = $request->query->getBoolean('closed');
        $form = $this->createForm(SearchRestaurantType::class, ['search' => $searchTerm, 'closed' => $closed], ['method' => 'GET', 'attr' => ['id' => 'search_form']]);

        return $this->render('restaurant/list.html.twig', [
            'search_form' => $form->createView(),
            'restaurants' => $this->getRestaurantList($request, $restaurantRepository),
        ]);
    }

    public function search(Request $request, RestaurantRepository $restaurantRepository)
    {
        $restaurants = $this->getRestaurantList($request, $restaurantRepository);
        $content = $this->render('restaurant/table.html.twig', ['restaurants' => $restaurants])->getContent();

        return new JsonResponse($content);
    }

    private function getRestaurantList(Request $request, RestaurantRepository $restaurantRepository)
    {
        $searchTerm = $request->query->get('s', '');
        $restaurantDate = null;

        if (0 === $request->query->getInt('closed', 0)) {
            $restaurantDate = new RestaurantDate();
        }

        return $restaurantRepository->search($searchTerm, $restaurantDate);
    }
}