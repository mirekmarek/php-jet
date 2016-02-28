<?php
/**
 *
 *
 *
 * AJAX controller class
 * @see Mvc/readme.txt
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @abstract
 *
 * @category Jet
 * @package Mvc
 * @subpackage Mvc_Controller
 */
namespace Jet;

//TODO: vyradit z jadra, bude to ciste vec UI kontroleru
abstract class Mvc_Controller_JetJS extends Mvc_Controller_Abstract {


    /**
     *
     *
     * @return bool
     */
    public function parseRequestURL() {

        $path_fragments = Mvc::getCurrentRouter()->getPathFragments();


        $group = array_shift($path_fragments);

        if($group!='Jet' && $group!='Jet.js' && $group!='modules') {
            return false;
        }

        foreach($path_fragments as $pf) {
            if(strpos($pf, '..')!==false) {
                return false;
            }
        }

        $JS_file_path = null;

        if($group=='Jet.js') {
            $JS_file_path = JET_PUBLIC_SCRIPTS_PATH.'Jet/Jet.js';
        } else {
            if($group=='Jet') {
                $JS_file_path = JET_PUBLIC_SCRIPTS_PATH.'Jet/Jet/'.implode('/',$path_fragments);
            } else {
                if($group=='modules') {
                    $module_name = array_shift($path_fragments);

                    if($module_name) {
                        $module_manifest = Application_Modules::getModuleManifest( $module_name );

                        if($module_manifest) {
                            Translator::setCurrentNamespace( $module_manifest->getName() );

                            if(!$path_fragments) {
                                $path_fragments[] = 'Main';
                            }


                            $JS_file_path = $module_manifest->getModuleDir().'JS/'.implode('/', $path_fragments).'.js';

                        }

                    }

                }
            }

        }

        if(!$JS_file_path) {
            return false;
        }

        if(!IO_File::exists($JS_file_path)){
            return false;
        }

        $JS = IO_File::read($JS_file_path);


        $JS = Javascript::translateJavaScript($JS);

        /*
        if($get_as_string){
            return $JS;
        }
        */

        Http_Headers::responseOK(
            [
                'Content-type' => 'text/javascript;charset=utf-8'
            ]
        );

        echo $JS;

        Debug_Profiler::setOutputIsJSON(true);
        Application::end();

        return true;
    }



    /**
     * @param string $module_action
     * @param string $controller_action
     * @param array $action_parameters
     *
     */
    public function responseAclAccessDenied($module_action, $controller_action, $action_parameters)
    {
    }

}