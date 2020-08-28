<?php
class CategoryContainers {

    private $con, $username;

    public function __construct($con, $username) {
        $this->con = $con;
        $this->username = $username;
    }

    public function showAllCategories() {
        $query = $this->con->prepare("SELECT * FROM categories");
        $query->execute();

        $html = "<div class='previewCategories'>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHTML($row, null, true, true);
        }

        return $html . "</div>";
    }

    // USE FOR TV SHOW PAGE
    public function showTVShowsCategories() {
        $query = $this->con->prepare("SELECT * FROM categories");
        $query->execute();

        $html = "<div class='previewCategories'>
                        <h2>TV Shows</h2>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHTML($row, null, true, false);
        }

        return $html . "</div>";
    }


    // USE FOR MOVIES PAGE
    public function showMoviesCategories() {
        $query = $this->con->prepare("SELECT * FROM categories");
        $query->execute();

        $html = "<div class='previewCategories'>
                        <h2>Movies</h2>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHTML($row, null, false, true);
        }

        return $html . "</div>";
    }


    //SHOW CATEGORY OTHER
    public function showCategory($categoryId, $title=null){
        $query = $this->con->prepare("SELECT * FROM categories WHERE id=:id");
        $query->bindValue(":id", $categoryId);
        $query->execute();

        $html = "<div class='previewCategories noScroll'>";

        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $html .= $this->getCategoryHTML($row, $title, true, true);  
        }
        return $html . "</div>";      
    }

    //PRIVATE CATEGORY TO SHOW HTML 
    private function getCategoryHTML($sqlData, $title, $tvShows, $movies) {
        $categoryId = $sqlData["id"];
        $title = $title == null ? $sqlData["name"] : $title;

        if($tvShows && $movies){
            $entities = EntityProvider::getEntities($this->con, $categoryId, 30);
        }
        else if($tvShows) {
            //GET tv show entities
            $entities = EntityProvider::getTVShowEntities($this->con, $categoryId, 30);
            
        }
        else {
            //GET movie entities
            $entities = EntityProvider::getMoviesEntities($this->con, $categoryId, 30);

        }
        if(sizeof($entities) == 0){
            return;
        }
        $entitiesHTML = "";
        $previewProvider = new PreviewProvider($this->con, $this->username);

        foreach($entities as $entity){
            $entitiesHTML .= $previewProvider->createEntityPreviewSquare($entity);
        }

        return "<div class='category'>
                    <a href='category.php?id=$categoryId'>
                        <h3>$title</h3>
                    </a>

                    <div class='entities'>
                        $entitiesHTML
                    </div>
                </div>";
    }

}
?>