<?php

namespace Action;

use App;

class TagAction {

	private App\Database $cnx;

	public function __construct()
	{
		$this->cnx 	= new App\Database;
 	}

    /**
     * Met à jour les tags associés à un topic
     * @param int $topicId L'ID du topic
     * @param array $tags Les nouveaux tags à associer au topic
     * @param int $userId L'ID de l'utilisateur qui modifie les tags
     * @return bool true si les tags ont été mis à jour avec succès, false sinon
     */
    public function updateTopicTags(int $topicId, array $tags, int $userID): bool 
    {
        // Vérifier que chaque tag est unique
        if (count($tags) != count(array_unique($tags))) 
        {
            return false; // Il y a des tags en double
        }
        // Supprimer les anciens tags qui ne font plus partie de la liste
        $this->cnx->Request("DELETE FROM f_topic_tags WHERE topic_id = ? AND tag_id NOT IN (" . implode(",", $tags) . ")",[$topicId]);
        // Ajouter les nouveaux tags qui ne sont pas déjà associés au topic
        // on vérifie que les tags existe via la class validator pas besoin de le faire ici
        foreach ($tags as $tag) 
        {
            if (!$this->getExistingTopicTags($topicId, $tag)) 
            {
                if($this->getExistingTopicTags($tag))
                {
                    $this->cnx->Request("INSERT INTO f_topic_tags (topic_id, tag_id, user_id) VALUES (?, ?, ?)",[$topicId, $tag, $userID]);
                }
            }
        }
        return true; // Les tags ont été mis à jour avec succès
    }

    /**
     * insertTagsOnNewTopic insert les tags pour le nouveau topic
     *
     * @param  array $tags
     * @param  int $userID
     * @param  int $lastID
     * @return void
     */
    public function insertTagsOnNewTopic(array $tags, int $userID, int $lastID): void
    {
        foreach($tags as $item)
        {
            $this->cnx->Request("INSERT INTO f_topic_tags SET tag_id = ?, user_id = ?, topic_id = ?",[$item, $userID , $lastID]);
        }
    }

    /**
     * Récupère les tags existants pour un topic donné dans la table topic_tags.
     * @param  $topic_id ID du topic pour lequel récupérer les tags.
     * @return array Tableau contenant les ID des tags existants pour le topic.
     */
    private function getExistingTopicTags($topic_id): array 
    {
        $stmt = $this->cnx->Request("SELECT tag_id FROM f_topic_tags WHERE topic_id = ?",[$topic_id]);
        $existing_topic_tags = array();
        foreach ($stmt as $row) {
            $existing_topic_tags[] = $row->tag_id;
        }
        return $existing_topic_tags;
    }


  }
