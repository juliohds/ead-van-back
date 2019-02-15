<?php

class NetworkConfigControllerTest extends TestCase
{

    public function testGetByNetworkId() {
        $token = AuthControllerTest::getToken($this);

        $headers = ['Authorization' => 'Bearer ' . $token->token];

        $r = $this->json('GET','/api/1/config/show-by-network-id',[],$headers);
        $json = (json_decode($r->response->getContent()));

        $this->assertEquals(2, $json->id);
        $this->assertEquals(1,$json->home_facets[0]->id);
        $this->assertEquals(2,$json->home_facets[0]->pivot->network_config_id);
        // var_dump($json->home_facets[0]->pivot->network_config_id);
    }

    public function testUpdateWithNetworkConfigPopulated() {
        $token = AuthControllerTest::getToken($this);

        $headers = ['Authorization' => 'Bearer ' . $token->token];

        $data = [
            "use_revisor" => true,
            "allow_anonymous_oda_suggestion" => true,
            "allow_class_plan" => true,
            "allow_sign_in" => true,
            "automatic_approve_comments" => true,
            "contact_email" => 'teste@teste.com',
            "ga_code" => null,
            "is_provider" => false,
            "lists_active" => false,
            "popular_objects_active" => false,
            "slogan" => null,
            "suggest_objects_active" => false,
            "suggest_thanks" => "Thanks",
            "survey_enabled" => false,
            "survey_thanks_1" => null,
            "survey_thanks_2" => null,
            "survey_url" => null,
        ];

        $this->json('PUT', '/api/3/config', $data, $headers);

        $r = $this->json('GET','/api/3/config/show-by-network-id',[],$headers);

        $json = (json_decode($r->response->getContent()));
        $this->assertEquals('teste@teste.com', $json->contact_email);
        $this->assertEquals(true, $json->use_revisor);
        $this->assertEquals(true, $json->allow_anonymous_oda_suggestion);
    }


    public function testUpdateWithNetworkConfigPopulatedWithVisualConfigs() {
        $token = AuthControllerTest::getToken($this);

        $headers = ['Authorization' => 'Bearer ' . $token->token];

        $data = [
            "use_revisor" => true,
            "allow_anonymous_oda_suggestion" => true,
            "allow_class_plan" => true,
            "allow_sign_in" => true,
            "automatic_approve_comments" => true,
            "contact_email" => 'dragon@lizzardsquad.com',
            "ga_code" => null,
            "is_provider" => false,
            "lists_active" => false,
            "popular_objects_active" => false,
            "slogan" => null,
            "suggest_objects_active" => false,
            "suggest_thanks" => "Thanks",
            "survey_enabled" => false,
            "survey_thanks_1" => null,
            "survey_thanks_2" => null,
            "survey_url" => null,
            "url_logo" => "https://cybermap.kaspersky.com/",
            "url_imagem_principal" => "http://map.norsecorp.com",
            "cor_primaria" => "#FFF",
            "cor_secundaria" => "#000",
            "network_config_menu_id" => 1,
            "network_rede_social_id" => 1,
            "odas_populares" => true,
            "sugestao_odas" => true,
            "listas" => true
        ];

        $this->json('PUT', '/api/3/config', $data, $headers);

        $r = $this->json('GET','/api/3/config/show-by-network-id',[],$headers);

        $json = (json_decode($r->response->getContent()));
        $this->assertEquals('dragon@lizzardsquad.com', $json->contact_email);
        $this->assertEquals(true, $json->use_revisor);
        $this->assertEquals(true, $json->allow_anonymous_oda_suggestion);
        $this->assertEquals("https://cybermap.kaspersky.com/", $json->url_logo);
        $this->assertEquals(1, $json->network_config_menu_id);
    }


    public function testUpdateWithEmptyNetworkConfig() {
        $token = AuthControllerTest::getToken($this);

        $headers = ['Authorization' => 'Bearer ' . $token->token];

        $r = $this->json('PUT','/api/4/config',[],$headers);
        $json = (json_decode($r->response->getContent()));
        $this->assertEquals(400, $r->response->getStatusCode());
    }

    public function testUpdateWithEmptyNetworkConfigHavingNetworkId() {
        $token = AuthControllerTest::getToken($this);

        $headers = ['Authorization' => 'Bearer ' . $token->token];

        $r = $this->json('PUT','/api/1/config',[],$headers);
        $json = (json_decode($r->response->getContent()));

        $this->assertEquals(400, $r->response->getStatusCode());
    }

}
