<?php

class SearchResultsProvider{
    private $con, $username;

    public function __construct($con, $username){
        $this->con = $con;
        $this->username = $username;
    }

    public function getResults($inputText){
        $entities = EntityProvider::getSearchEntities($this->con, $inputText);

        $html = "<div class='previewCategories noScroll'>";
        $html .=$this->getResultsHTML($entities);
        
       return $html. "</div>";
    }

    private function getResultsHTML($entities){
        if(sizeof($entities) == 0){
            return;
        }
        $entitiesHTML = "";
        $previewProvider = new PreviewProvider($this->con, $this->username);

        foreach($entities as $entity){
            $entitiesHTML .= $previewProvider->createEntityPreviewSquare($entity);
        }

        return "<div class='category'>

                    <div class='entities'>
                        $entitiesHTML
                    </div>
                </div>";
    }
}

?>