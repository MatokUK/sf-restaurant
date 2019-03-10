<?php

namespace App\Tests\Command\RecordConverter;

use App\Command\RecordConverter\DataTypeB;
use App\Entity\Restaurant;
use PHPUnit\Framework\TestCase;

class DataTypeBTest extends TestCase
{
    /**
     * @param array      $data
     * @param Restaurant $expectedRestaurant
     * @dataProvider createRestaurantData
     */
    public function testCreateRestaurant(array $data, Restaurant $expectedRestaurant)
    {
        $typeA = new DataTypeB();
        $restaurant = $typeA->createRestaurant($data);

        $this->assertEquals($expectedRestaurant->getTitle(), $restaurant->getTitle());
    }

    public function testLessItemsThrowException()
    {
        $this->expectException(\RangeException::class);
        $this->expectExceptionMessage('Expected array of length 2, but given array has length 1');

        $typeA = new DataTypeB();
        $restaurant = $typeA->createRestaurant(['Subway']);

    }

    public function createRestaurantData()
    {
        $data1 = ['Bamboo', 'Mon-Sun 11:30 am - 9 pm'];
        $restaurant1 = new Restaurant();
        $restaurant1->setTitle('Bamboo');

        return [
            [$data1, $restaurant1],
        ];
    }
}