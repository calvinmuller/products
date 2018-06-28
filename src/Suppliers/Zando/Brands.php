<?php
namespace Istreet\Products\Suppliers\Zando;


class Brands extends Api {

    /**
     *
     */
    public function all() {

        return $this->get('/api/startup/brandsnavigation/');
    }

}
