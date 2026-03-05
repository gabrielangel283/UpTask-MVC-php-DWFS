<?php

namespace Model;


class Proyecto extends ActiveRecord
{
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id', 'proyecto', 'url', 'propietarioId'];

    public $id;
    public $proyecto;
    public $url;
    public $propietarioId;

    public function __construct($agrs = [])
    {
        $this->id = $agrs['id'] ?? null;
        $this->proyecto = $agrs['proyecto'] ?? '';
        $this->url = $agrs['url'] ?? '';
        $this->propietarioId = $agrs['propietarioId'] ?? '';
    }

    public function validarProyecto()
    {
        self::$alertas = [];

        if (!$this->proyecto) {
            self::$alertas['error'][] = "El nombre del proyecto es obligatorio";
        }

        return self::$alertas;
    }
}
