<?php
class SeasonProvider {
    private $con, $username;

    public function __construct($con, $username) {
        $this->con = $con;
        $this->username = $username;
    }

    public function create($entity) {
        $seasons = $entity->getSeasons();

        if(sizeof($seasons) == 0) {
            return;
        }

        $seasonsHTML = "";
        foreach($seasons as $season){
            $seasonNumber =  $season->getSeasonNumber();

            $videosHTML ="";
            foreach($season->getVideos() as $video) {
                $videosHTML .= $this->createVideoSquare($video);
            }

            $seasonsHTML .= "<div class='season'>
                                <h3>Season $seasonNumber</h3>
                                <div class='videos'>
                                $videosHTML
                                </div>
            
            </div>";
        }
        return $seasonsHTML;
    }

    private function createVideoSquare($video){
        $id = $video->getId();
        $thumbnail = $video->getThumbnail();
        $title = $video->getTitle();
        $description = $video->getDescription();
        $episodeNumber = $video->getEpisodeNumber();
        $hasSeen = $video->hasSeen($this->username) ? "<i class='fas fa-check-circle seen'></i>" : "";

        return "<a href='watch.php?id=$id'>
                    <div class='episodeContainer'>
                        <div class='contents'>

                            <img src='$thumbnail'>

                            <div class='videoInfo'>
                                <h4>$episodeNumber. $title</h4>
                                <span>$description</span>
                            </div>
                            $hasSeen
                        </div>
                    </div>
                </a>"; 
    }
}
?>