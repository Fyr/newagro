<?
    echo $this->Form->input('UserCompany.company_name', array('label' => array('text' => '<span class="star">*</span> '.__('Company name'))));
	echo $this->Form->input('UserCompany.company_uuid', array('label' => array('text' => '<span class="star">*</span> '.__('Company UUID'))));
	echo $this->Form->input('UserCompany.address', array('label' => array('text' => '<span class="star">*</span> '.__('Legal address'))));
	echo $this->Form->input('UserCompany.contact_person', array('label' => array('text' => '<span class="star">*</span> '.__('Contact Person'))));
	echo $this->Form->input('UserCompany.contact_phone', array('label' => array('text' => '<span class="star">*</span> '.__('Contact Phone'))));
