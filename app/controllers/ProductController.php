<?php

class ProductController extends Controller
{
    public function index()
    {
        $this->renderView('products/index', [], 'Proizvodi');
    }
}