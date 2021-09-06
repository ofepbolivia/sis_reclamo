
<?php

class GenerarArbol
{
    private $tree = array();
    private $index = array();
    private $cont = 0;
    
    public function addChild($child, $parentKey = null)
    {

        $key = isset($child["id"]) ? $child["id"] : 'item_' . $this->cont;
        $child["leaf"] = true;
        if ($this->containsKey($parentKey)) {
            //added to the existing node
            $this->index[$key] =& $child;
            $parent =& $this->index[$parentKey];
            if (isset($parent["children"])) {
                $parent["children"][] =& $child;
            } else {
                $parent["leaf"] = false;
                $parent["children"] = array();
                $parent["children"][] =& $child;
            }
        } else {
            //added to the root
            $this->index[$key] =& $child;
            $this->tree[] =& $child;
        }
        $this->cont++;
    }

    public function getNode($key)
    {
        return $this->index[$key];
    }

    public function removeNode($key)
    {
        //unset($this->index[key]);
    }

    public function containsKey($key)
    {
        return isset($this->index[$key]);
    }

    public function toJson()
    {
        return json_encode($this->tree);
    }

}
?>