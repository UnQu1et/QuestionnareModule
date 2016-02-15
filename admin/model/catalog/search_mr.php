<?php
class ModelCatalogSearchMr extends Model {

  public function getDefaultOptions() {
  
    return array( 
      'use_morphology' => 1,
      'use_relevance' => 1,
      'title_string_weight' => 60,  
      'title_word_weight' => 30,
      'description_string_weight' => 15,
      'description_word_weight' => 10,  
      'tag_word_weight' => 20,
    );    
  }

}