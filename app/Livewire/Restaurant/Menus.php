<?php

namespace App\Livewire\Restaurant;

use Livewire\Component;

class Menus extends Component
{
    public string $activeTab = 'food';

    public array $foods = [
        ['name' => 'قابلی مخصوص', 'price' => '320', 'image' => 'https://images.unsplash.com/photo-1604908177522-0408a2f3c3a4'],
        ['name' => 'قابلی مرغ', 'price' => '280', 'image' => 'https://images.unsplash.com/photo-1625944521648-8c4b44b9c9d4'],
        ['name' => 'قابلی گوشت', 'price' => '350', 'image' => 'https://images.unsplash.com/photo-1628294895950-9805252327bc'],
        ['name' => 'کباب کوبیده', 'price' => '300', 'image' => 'https://images.unsplash.com/photo-1551183053-bf91a1d81141'],
        ['name' => 'کباب برگ', 'price' => '420', 'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947'],
        ['name' => 'جوجه کباب', 'price' => '260', 'image' => 'https://images.unsplash.com/photo-1600628422019-5c6a7b6b9c48'],
        ['name' => 'زرشک پلو', 'price' => '290', 'image' => 'https://images.unsplash.com/photo-1628294896116-7fcd0db1f2fd'],
        ['name' => 'قورمه سبزی', 'price' => '270', 'image' => 'https://images.unsplash.com/photo-1598514982205-f96fbe8fd65f'],
        ['name' => 'قیمه', 'price' => '260', 'image' => 'https://images.unsplash.com/photo-1605478371866-2c5b8f9b99b7'],
        ['name' => 'فسنجان', 'price' => '360', 'image' => 'https://images.unsplash.com/photo-1617191519105-d07b98cfd1fc'],
        ['name' => 'ماکارونی', 'price' => '210', 'image' => 'https://images.unsplash.com/photo-1589308078054-8329a3c6c9a1'],
        ['name' => 'کشک بادمجان', 'price' => '200', 'image' => 'https://images.unsplash.com/photo-1626078299999-0b0a5f87d0b3'],
    ];

    public array $drinks = [
        ['name' => 'کوکاکولا', 'price' => '50', 'image' => 'https://images.unsplash.com/photo-1610878180933-123728745d22'],
        ['name' => 'فانتا', 'price' => '50', 'image' => 'https://images.unsplash.com/photo-1586190848861-99aa4a171e90'],
        ['name' => 'دوغ سنتی', 'price' => '60', 'image' => 'https://images.unsplash.com/photo-1627485937980-221c88ac04ff'],
        ['name' => 'آب پرتقال', 'price' => '70', 'image' => 'https://images.unsplash.com/photo-1571689936042-16b06c5bb7cc'],
        ['name' => 'موهیتو', 'price' => '110', 'image' => 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62'],
        ['name' => 'قهوه لاته', 'price' => '120', 'image' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93'],
        ['name' => 'چای', 'price' => '25', 'image' => 'https://images.unsplash.com/photo-1544787219-7f47ccb76574'],
        ['name' => 'شیر موز', 'price' => '100', 'image' => 'https://images.unsplash.com/photo-1577805947697-89e18249d767'],
    ];
    public function render()
    {
        return view('livewire.restaurant.menus');
    }
}