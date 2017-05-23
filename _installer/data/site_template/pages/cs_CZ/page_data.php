<?php
return [
    'name' => 'Homepage',
    'title' => 'Hlavní stránka',
    'menu_title' => 'Hlavní stránka',
    'breadcrumb_title' => 'Hlavní stránka',
    'layout_script_name' => 'default',
    'meta_tags' => [
        [
            'attribute'   => 'Meta1attribute',
            'attribute_value' => 'Meta 1 attribute value',
            'content' => 'Meta 1 content'
        ],
        [
            'attribute'   => 'Meta2attribute',
            'attribute_value' => 'Meta 2 attribute value',
            'content' => 'Meta 2 content'
        ],
        [
            'attribute'   => 'Meta3attribute',
            'attribute_value' => 'Meta 3 attribute value',
            'content' => 'Meta 3 content'
        ],
    ],
    'contents' => [
	    [
		    'module_name' => 'JetExample.TestModule',
		    'controller_action' => 'test_mvc_info',
		    'output_position' => 'right',
		    'output_position_order' => 1
	    ],
	    [
		    'output_position' => '',
		    'output_position_order' => 1,
	        'output' => <<<EOT
<h1>Vítejte!</h1>

<p>Toto je ukázková aplikace PHP frameworku Jet. Co Jet umí?</p>
<ul>
	<li>Modulární aplikace - snadno znovu použitelný kód a moduly</li>
	<li>Důraz na bezpečnost</li
	<li>Důraz na výkon</li>
	<li>Snadné učení</li>
	<li>Navrženo a vyvíjeno na základě poznatků z reálné praxe v prostředí různorodých projektů od malého rozsahu po opravdu velké projekty</li>
	<li>MVC</li>
	<li>ORM</li>
	<li>ACL</li>
</ul>

<p>Tato aplikace může sloužit jako základ vašeho projektu, či produktu.</p>
EOT
	    ]

    ]
];

