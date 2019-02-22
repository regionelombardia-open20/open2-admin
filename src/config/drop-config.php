<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\admin\config
 * @category   CategoryName
 */

return [
    'models' => [
        'lispa\amos\documenti\models\Documenti' => [
            'titolo',
            'sottotitolo',
            'descrizione_breve',
            'descrizione'
        ],
        'lispa\amos\discussioni\models\DiscussioniTopic' => [
            'titolo',
            'testo',
        ],
        'lispa\amos\discussioni\models\DiscussioniCommenti' => [
            'titolo',
            'testo',
        ],
        'lispa\amos\news\models\News' => [
            'titolo',
            'sottotitolo',
            'descrizione',
            'descrizione_breve',
        ],
        'lispa\amos\comments\models\Comment' => [
            'comment_text',
        ],
        'lispa\amos\comments\models\CommentReply' => [
            'comment_reply_text',
        ],
        'lispa\amos\projectmanagement\models\Projects' => [
            'name',
            'summary',
        ],
        'lispa\amos\events\models\Event' => [
            'status',
            'title',
            'summary',
            'description',
        ],
        'lispa\amos\partnershipprofiles\models\PartnershipProfiles' => [
            'title',
            'short_description',
            'extended_description',
            'advantages_innovative_aspects',
            'other_prospect_desired_collab',
            'expected_contribution',
            'contact_person',
            'english_title',
            'english_short_description',
            'english_extended_description',
            'other_work_language',
            'other_development_stage',
            'other_intellectual_property',
        ],
        'lispa\amos\partnershipprofiles\models\ExpressionsOfInterest' => [
            'status',
            'partnership_offered',
            'additional_information',
            'clarifications',
            'user_network_reference_classname',
        ],
        'amos\results\models\Result' => [
            'title',
            'summary',
            'project_proposal',
            'initiative_text',
            'website',
            'innovation_description',
            'insights'
        ],
        'amos\results\models\ResultProposal' => [
            'title',
            'summary',
            'project_proposal',
            'initiative_text',
            'website',
        ],
        'lispa\amos\sondaggi\models\Sondaggi' => [
            'titolo',
            'descrizione'
        ],
        'lispa\amos\sondaggi\models\SondaggiDomande' => [
            'domanda'
        ],
        'lispa\amos\sondaggi\models\SondaggiRisposte' => [
            'risposta_libera'
        ],
        'lispa\amos\organizzazioni\models\Profilo' => [
            'name',
            'presentazione_della_organizzaz',
            'principali_ambiti_di_attivita_',
            'ambiti_tecnologici_su_cui_siet',
            'tipologia_di_organizzazione',
            'forma_legale',
            'sito_web',
            'indirizzo',
            'la_sede_legale_e_la_stessa_del',
            'sede_legale_indirizzo',
            'responsabile',
            'rappresentante_legale',
            'referente_operativo',
        ],
        'lispa\amos\showcaseprojects\models\ShowcaseProject' => [
            'title',
            'summary',
            'insights',
        ],
        'lispa\amos\showcaseprojects\models\ShowcaseProjectProposal' => [
            'title',
            'summary',
        ],
        'lispa\amos\een\models\EenPartnershipProposal' => [
            'company_certifications_list',
            'company_experience',
            'company_languages_list',
            'contact_email',
            'contact_fullname',
            'contact_organization',
            'content_description',
            'content_summary',
            'content_title',
            'cooperation_exploitation_list',
            'cooperation_ipr_comment',
            'cooperation_ipr_status',
            'cooperation_partner_area',
            'cooperation_partner_sought',
            'cooperation_partner_task',
            'cooperation_plusvalue',
            'cooperation_stagedev_comment',
            'cooperation_stagedev_stage',
            'reference_external',
            'reference_internal',
            'tags_not_found',
        ],
        'lispa\amos\proposte_collaborazione\models\ProposteDiCollaborazione' => [
            'titolo',
            'persona_di_riferimento_e_conta',
            'tipo_di_collaborazione_prospet',
            'altro_tipo_di_collaborazione_p',
            'titolo_inglese',
            'descrizione_sintetica_inglese',
            'descrizione_estesa_inglese',
            'altra_lingua_di_lavoro',
            'altro_stadio_di_sviluppo_dei_c',
            'altra_proprieta_intellettuale_'
        ],
        'lispa\amos\proposte_collaborazione\models\ManifestazioniInteresse' => [
            'contributo_offerto',
            'informazioni_aggiuntive',
            'chiarimenti'
        ],
    ]
];
