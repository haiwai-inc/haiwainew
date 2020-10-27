<?php
class article_indexing extends Model
{
    protected $tableName = "indexing";
    protected $dbinfo    = array("config" => "article", "type" => "MySQL");

    public function get_article_info($where)
    {
        $article_post_obj = load("article_post");
        $article_tag_obj  = load("article_post_tag");
        $article_buzz_obj = load("article_post_buzz");
        $tag_obj          = load("article_tag");
        try {
            $articles         = $this->getAll("*", $where);
            $id_list          = [];
            $post_id_list     = [];
            $article_post_map = [];
            $article_tag_map  = [];
            $article_buzz_map = [];
            foreach (range(0, 9) as $number) {
                $id_list[]      = [];
                $post_id_list[] = [];
            }

            foreach ($articles as $article) {
                $table_id                      = $article['userID'] % 10;
                $tag_table_id                  = $article['postID'] % 10;
                $id_list[$table_id][]          = $article['postID'];
                $post_id_list[$tag_table_id][] = $article['postID'];
                unset($table_id);
                unset($tag_table_id);
            }

            $tag_id_list = [];

            //Get article title, content and tagIDs
            foreach (range(0, 9) as $i) {
                $ids           = $id_list[$i];
                $tag_post_ids  = $post_id_list[$i];
                $article_posts = [];
                if (!empty($ids)) {
                    $article_posts = $article_post_obj->getAll("*", ["OR" => ["id" => $ids]], "post_{$i}");
                }
                $article_tags = [];
                $article_buzz = [];
                if (!empty($tag_post_ids)) {
                    $article_tags = $article_tag_obj->getAll("*", ["OR" => ["postID" => $tag_post_ids]], "post_tag_{$i}");
                    $article_buzz = $article_buzz_obj->getAll("*", ["OR" => ["postID" => $tag_post_ids]], "post_buzz_{$i}");
                }

                foreach ($article_posts as $post) {
                    $article_post_map[$post['id']] = $post;
                }

                foreach ($article_tags as $tag) {
                    if (empty($article_tag_map[$tag['postID']])) {
                        $article_tag_map[$tag['postID']] = [];
                    }
                    $article_tag_map[$tag['postID']][] = $tag["tagID"];
                    $tag_id_list[]                     = $tag["tagID"];
                }

                foreach ($article_buzz as $buzz) {
                    if (empty($article_buzz_map[$buzz['postID']])) {
                        $article_buzz_map[$buzz['postID']] = [];
                    }
                    $article_buzz_map[$buzz['postID']][] = $buzz['userID'];
                }

                unset($ids);
                unset($tag_post_ids);
                unset($article_posts);
                unset($article_tags);
            }

            //Get tag info
            $tag_id_map = [];
            $tags       = [];
            if (!empty($tag_id_list)) {
                $tags = $tag_obj->getAll(["id", "name"], ["OR" => ['ID' => $tag_id_list]]);
            }
            foreach ($tags as $tag) {
                $tag_id_map[$tag['id']] = $tag['name'];
            }
            unset($tags);

            $final_list = [];

            if (count($articles) < 1) {
                return [];
            }
            foreach (range(0, count($articles) - 1) as $i) {
                $postID = $articles[$i]['postID'];
                if (empty($article_post_map[$postID])) {
                    continue;
                }

                $post_info                      = $article_post_map[$postID];
                $articles[$i]['title']          = $post_info['title'];
                $msgbody                        = strip_tags($post_info['msgbody']);
                $articles[$i]['msgbody_origin'] = $post_info['msgbody'];
                $articles[$i]['msgbody']        = strip_tags($post_info['msgbody']);
                $articles[$i]['pic']            = strip_tags($post_info['pic']);
                $articles[$i]['buzz']           = [];
                if (!empty($article_buzz_map[$postID])) {
                    $articles[$i]['buzz'] = $article_buzz_map[$postID];
                }
                $tag_list = [];
                if (!empty($article_tag_map[$postID])) {
                    $tag_list = $article_tag_map[$postID];
                }

                $articles[$i]['tags'] = $tag_list;
                $final_list[]         = $articles[$i];
                unset($msgbody);
                unset($post_info);
                unset($tag_list);
                unset($postID);
            }

            unset($id_list);
            unset($post_id_list);
            unset($article_post_map);
            unset($article_tag_map);
            unset($articles);
            unset($tag_id_list);
            unset($tag_id_map);
            return $final_list;
            unset($final_list);
        } catch (Exception $e) {
            return [];
        }
    }
}
