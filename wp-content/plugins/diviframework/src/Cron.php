<?php

namespace DiviFramework\Hub;

/**
 * Cron Class
 */
class Cron {

	public function cron_schedules($schedules) {

		$schedules['every_six_days'] = array(
			'interval' => 518400,
			'display' => __('Every 6 days', 'textdomain'),
		);

		return $schedules;
	}

}
