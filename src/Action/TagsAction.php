<?php

namespace Action;

use App;
use Framework;

class TagsAction {
    
	private App\App $app;
	private App\Database $cnx;
	private Framework\Router $router;

	public function __construct()
	{
		$this->app 			= new App\App;
		$this->cnx 			= new App\Database;
		$this->router 		= new Framework\Router;
 	}
    
    /**
     * UPDATE|INSERT les tags d'un topic donné dans la table topic_tags.
     * @param int $topic_id ID du topic à mettre à jour.
     * @param int $user_id ID de l'auteur | ajout plus tard l'id du modo ou admin qui éditera.
     * @param array $tags Tableau contenant les nouveaux tags à associer au topic.
     * @param bool $update si null on fais un insert si true on fais un update 
     * @return array Tableau contenant les ID des tags mis à jour.
     */
    public function fromTopicTags(int $topic_id,int $user_id, array $tags,bool $update = NULL): array 
    {
        $existing_topic_tags = $this->getExistingTopicTags($topic_id);
        $updated_tags = array();
        foreach ($tags as $tag) {
            // Vérifier que le tag est un entier
            if (is_int($tag) && count($updated_tags) <= 4) 
            {
                if (!in_array($tag, $existing_topic_tags)) 
                {
                    if (count($existing_topic_tags) <= 4) {
                        $this->deleteTopicTag($topic_id, $existing_topic_tags[0]);
                        array_shift($existing_topic_tags);
                    }
                    $this->saveTopicTag($topic_id, $user_id, $tag, $update);
                    $updated_tags[] = $tag;
                }
            }
        }
        return $updated_tags;
    }

    /**
     * Récupère les tags existants pour un topic donné dans la table topic_tags.
     * @param int $topic_id ID du topic pour lequel récupérer les tags.
     * @return array Tableau contenant les ID des tags existants pour le topic.
     */
    private function getExistingTopicTags(int $topic_id): array 
    {
        $stmt = $this->cnx->Request("SELECT tag_id FROM f_topic_tags WHERE topic_id = ?",[$topic_id]);
        $existing_topic_tags = array();
        foreach ($stmt as $row) {
            $existing_topic_tags[] = $row['tag_id'];
        }
        return $existing_topic_tags;
    }

     /**
     * UPDATE|INSERT a jour un nouveau lien entre un topic et un tag dans la table de jonction topic_tags.
     * @param int $topic_id ID du topic à associer au tag.
     * @param int $tag_id ID du tag à associer au topic.
     * @param bool $update si null on fais un insert si true on fais un update 
     * @param int $user_id ID de l'auteur | ajout plus tard l'id du modo ou admin qui éditera.
    */
    private function saveTopicTag(int $topic_id, int $user_id, int $tag_id,bool $update): void
    {
        if($update == NULL){
            $this->cnx->Request("INSERT INTO f_topic_tags SET topic_id = ?, user_id = ?, tag_id = ?",[$topic_id, $user_id, $tag_id]);
        }else{
            $this->cnx->Request("UPDATE FROM f_topic_tags SET tag_id = ? WHERE id = ?",[$tag_id]);
        }
    }

    /**
     * Supprime le lien entre un topic et un tag dans la table de jonction topic_tags.
     * @param int $topic_id ID du topic associé au tag à supprimer.
     * @param int $tag_id ID du tag à supprimer.
     */
    private function deleteTopicTag(int $topic_id,int $tag_id): void
    {
        $this->cnx->Request("DELETE FROM f_topic_tags WHERE topic_id = ? AND tag_id = ?",[$topic_id, $tag_id]);
    }

}
