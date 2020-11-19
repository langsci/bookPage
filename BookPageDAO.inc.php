<?php

/**
 * @file plugins/generic/bookPage/BookPageDAO.inc.php
 *
 * Copyright (c) 2016 - 2020 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class BookPageDAO
 * @ingroup plugins_generic_bookPage
 *
 * @brief Operations for retrieving reviews.
 */

class BookPageDAO extends DAO {
	
	/**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * Retrieve all reviews of a submission.
	 * @param $submissionId int
	 * @return reviews array
	 */
	function getReviewsBySubmissionId($submissionId){
		
		$result = $this->retrieve(
			'SELECT * FROM langsci_review_links WHERE submission_id ='.$submissionId
		);
		
		// get date format from config
		$dateFormat = Config::getVar('general', 'date_format_long');
		
		$reviews = array();
		
		while (!$result->EOF) {
			$row = $result->getRowAssoc(false);
			$value['reviewer'] = $this->convertFromDB($row['reviewer'], 1);
			$value['date'] = strftime($dateFormat, strtotime($this->convertFromDB($row['date'], 1)));
			$value['link'] = $this->convertFromDB($row['link'], 1);
			$value['name'] = $this->convertFromDB($row['link_name'], 1);
			$value['money_quote'] = $this->convertFromDB($row['money_quote'], 1);
			$reviews[$row['review_id']] = $value;
			$result->MoveNext();
		}
		$result->Close();
		return $reviews;
		
	}
	
}

?>
