<?php
/**
 * Powerd by ArPHP.
 *
 * Controller.
 *
 * @author ycassnr <ycassnr@gmail.com>
 */

use \Core\Ar;

/**
 * Default Controller of webapp.
 */
class Model extends \Core\ArModel {

    public function index() {
        echo 'model';
    }

    /**
     * add columns.
     *
     * @return array
     */
    public function addClumns($data, $key, $value = '')
    {
        if (Ar::c('validator.validator')->checkMutiArray($data)) :
            foreach ($data as &$d) :
                $d[$key] = $value;
            endforeach;
        elseif (is_array($data)) :
            $data[$key] = $value;
        endif;

        return $data;

    }

}