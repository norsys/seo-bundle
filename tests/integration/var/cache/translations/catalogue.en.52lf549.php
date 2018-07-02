<?php

use Symfony\Component\Translation\MessageCatalogue;

$catalogue = new MessageCatalogue('en', array (
  'seo' => 
  array (
    'my_page.content.description' => 'My translated description',
    'defaults.title' => 'My super web site',
    'home.description' => 'Home page of my super web site',
    'home.title' => 'Home | My super web site',
  ),
));


return $catalogue;
