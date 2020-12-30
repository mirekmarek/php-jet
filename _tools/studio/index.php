<?php
namespace JetStudio;

require 'application/init.php';

Application::setCurrentPart( 'welcome' );
Application::handleAction();
Application::output( Application::getView()->render('main') );
Application::renderLayout();


/*
$parser = new ClassParser( file_get_contents(__DIR__.'/../../application/Modules/Content/Articles/Article.php') );

foreach( $parser->classes as $name=>$class ) {
	foreach($class->attributes as $attr ) {


		for($c=$attr->start_token->index;$c<=$attr->end_token->index;$c++) {
			//echo $this->tokens[$c]->debug_getInfo();
			echo $parser->tokens[$c]->text;
		}


		//var_dump($attr->name, $attr->arguments);
	}

	echo PHP_EOL.PHP_EOL;

	foreach( $class->properties as $name => $property ) {
		var_dump($name);

		foreach($property->attributes as $attr) {
			var_dump($attr->name, $attr->arguments);
		}

		echo $property->toString();

		echo PHP_EOL.PHP_EOL;
	}


}

/*
$attribute = new ClassCreator_Attribute( 'DataModel_Definition' );

$attribute->setArgument('number', 1234);
$attribute->setArgument('float', 3.14);
$attribute->setArgument('bool', true);
$attribute->setArgument('className', 'SomeClass::class');
$attribute->setArgument('assoc_array', [
	'number' => 4321,
	'float' => 6.28,
	'str' => "Mc'debil ",
	'str2' => 'aaaa',
	'className' => 'SomeClass::class',
	[1,2,3,4,5,6,7,
	 [
		 'number' => 4321,
		 'float' => 6.28,
		 'className' => 'SomeClass::class',
		 [1,2,3,4,5,6,7],

	 ]
    ],

]);

echo $attribute->toString(1);
*/