<?php

namespace App\Tests\Command\RecordConverter;


use App\Command\RecordConverter\DataTypeA;
use App\Entity\Restaurant;
use PHPUnit\Framework\TestCase;

class DataTypeATest extends TestCase
{
    /**
     * @param array      $data
     * @param Restaurant $expectedRestaurant
     * @dataProvider createRestaurantData
     */
    public function testCreateRestaurant(array $data, Restaurant $expectedRestaurant)
    {
        $typeA = new DataTypeA();
        $restaurant = $typeA->createRestaurant($data);

        $this->assertEquals($expectedRestaurant->getTitle(), $restaurant->getTitle());
        $this->assertEquals($expectedRestaurant->getCuisine(), $restaurant->getCuisine());
        $this->assertEquals($expectedRestaurant->getDescription(), $restaurant->getDescription());
    }

    public function testLessItemsThrowException()
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('Expected array of length 10, but given array has length 1');

        $typeA = new DataTypeA();
        $restaurant = $typeA->createRestaurant(['Subway']);

    }

    public function createRestaurantData()
    {
        $data1 = ['Bamboo', 100, 'vietnamese','10:00:00', '22:00:00', 'Mo,Tu,We,Th,Fr,Sa,Su', null, 5, 'Bratislava, Karlova Ves', 'Best vietnamese restaurant'];
        $restaurant1 = new Restaurant();
        $restaurant1->setTitle('Bamboo')
            ->setCuisine('vietnamese')
            ->setDescription('Best vietnamese restaurant');

        return [
            [$data1, $restaurant1],
        ];
    }
}