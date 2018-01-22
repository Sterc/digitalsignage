<?php

	class DigitalSignagePlayersSchedules extends xPDOSimpleObject {
		/**
		 * @access public.
		 * @return Boolean|Object.
		 */
		public function getBroadcast() {
			if (null !== ($broadcast = $this->getOne('getBroadcast'))) {
				if ($broadcast->hasResource()) {
					return $broadcast;	
				}
			} 
			
			return false;
		}
		
		/**
		 * @access public.
		 * @param String $type.
		 * @return Boolean.
		 */
		public function is($type) {
			return $type == $this->type;
		}

		/**
		 * @access public.
		 * @param String $timestamp.
		 * @param String $type.
		 * @param Mixed $value.
		 * @return Boolean.
		 */
		public function isEmpty($timestamp, $type, $value = true) {
			if ('date' == $type) {
				if (in_array($timestamp, array('0000-00-00', '', null))) {
					return $value;
				}
			} else if ('time' == $type) {
				if (in_array($timestamp, array('00:00:00', '23:59:59', '', null))) {
					return $value;
				}
			}

			if (true !== $value) {
				return $timestamp;
			}

			return false;
		}

		/**
		 * @access public.
		 * @param String $start.
		 * @param String $end.
		 * @return Boolean.
		 */
		public function isEntireDay($start = null, $end = null) {
			if (null !== $start && $end !== null) {
				return $this->isEmpty($start, 'time') && $this->isEmpty($end, 'time');
			}

			return $this->isEmpty($this->start_time, 'time') && $this->isEmpty($this->end_time, 'time');
		}

		/**
		 * @access public.
		 * @return Boolean.
		 */
		public function isSingleDay() {
			return $this->is('day') || $this->isEmpty($this->end_date, 'date') || $this->start_date == $this->end_date;
		}

		/**
		 * @access public.
		 * @return String.
		 */
		public function getDayOfWeek() {
			return date('l', strtotime('Sunday +'.$this->day.' Days'));
		}

		/**
		 * @access public.
		 * @param String|Integer $timestamp.
		 * @return String.
		 */
		public function getFirstDate($timestamp = null) {
			if (null !== $timestamp) {
				if (is_string($timestamp)) {
					$timestamp = strtotime($timestamp);
				}

				return date('Y-m-d '.$this->start_time, $timestamp);
			} else {
				return date($this->start_date.' '.$this->start_time);
			}
		}

		/**
		 * @access public.
		 * @param String|Integer $timestamp.
		 * @return String.
		 */
		public function getLastDate($timestamp = null) {
			if (null !== $timestamp) {
				if (is_string($timestamp)) {
					$timestamp = strtotime($timestamp);
				}

				$timestamp = strtotime(date('Y-m-d '.$this->end_time, $timestamp));
			} else {
				if ($this->isEmpty($this->end_date, 'date')) {
					$timestamp = strtotime(date($this->start_date.' '.$this->end_time));
				} else {
					$timestamp = strtotime(date($this->end_date.' '.$this->end_time));
				}
			}

			if ($this->isEmpty($this->end_time, 'time')) {
				$timestamp += 60 * 60 * 24;
			}

			return date('Y-m-d H:i:s', $timestamp);
		}

		/**
		 * @access public.
		 * @param Array $start.
		 * @param Array $end.
		 * @return Boolean.
		 */
		public function isScheduledFor($start, $end) {
			$start = array(
				'date'	=> $this->isEmpty($start['date'], 'date', '0000-00-00'),
				'time'	=> $this->isEmpty($start['time'], 'time', '00:00:00'),
				'stamp'	=> '',
			);

			$end = array(
				'date'	=> $this->isEmpty($end['date'], 'date', $start['date']),
				'time'	=> $this->isEmpty($end['time'], 'time', '00:00:00'),
				'stamp'	=> ''
			);

			$start['stamp'] 	= strtotime(date('Y-m-d H:i:s', strtotime(date($start['date'].' '.$start['time']))));
			$end['stamp'] 		= strtotime(date('Y-m-d H:i:s', strtotime(date($end['date'].' '.$end['time']))));

			if ($this->isEmpty($end['time'], 'time')) {
				$end['stamp'] = strtotime(date('Y-m-d H:i:s', $end['stamp'] + (60 * 60 * 24)));
			}
			
			if ($this->is('day')) {
				if (date('w', $start['stamp']) == $this->day) {
					if (strtotime($this->getFirstDate($start['stamp'])) < $start['stamp'] && strtotime($this->getLastDate($start['stamp'])) > $start['stamp']) {
						return true;
					}

					if (strtotime($this->getFirstDate($start['stamp'])) < $end['stamp'] && strtotime($this->getLastDate($start['stamp'])) > $end['stamp']) {
						return true;
					}

					if ($start['stamp'] < strtotime($this->getFirstDate($start['stamp'])) && $end['stamp'] > strtotime($this->getFirstDate($start['stamp']))) {
						return true;
					}

					if ($start['stamp'] < strtotime($this->getLastDate($start['stamp'])) && $end['stamp'] > strtotime($this->getLastDate($start['stamp']))) {
						return true;
					}
				}
			} else {
				if (strtotime($this->getFirstDate()) < $start['stamp'] && strtotime($this->getLastDate()) > $start['stamp']) {
					return true;
				}

				if (strtotime($this->getFirstDate()) < $end['stamp'] && strtotime($this->getLastDate()) > $end['stamp']) {
					return true;
				}

				if ($start['stamp'] < strtotime($this->getFirstDate()) && $end['stamp'] > strtotime($this->getFirstDate())) {
					return true;
				}

				if ($start['stamp'] < strtotime($this->getLastDate()) && $end['stamp'] > strtotime($this->getLastDate())) {
					return true;
				}
			}

			return false;
		}

		/**
		 * @access public.
		 * @param Array $start.
		 * @param Array $end.
		 * @return Array.
		 */
		public function getRange($start = array(), $end = array()) {
			$dates = array();

			if ($this->is('day')) {
				$end = array(
					'date'	=> $end['date'],
					'time'	=> $this->isEmpty($end['time'], 'time', '23:59:59')
				);

				$type 		= $this->getDayOfWeek();

				$startDate 	= strtotime(date('Y-m-d 00:00:00', strtotime($start['date'])));
				$endDate	= strtotime(date('Y-m-d 00:00:00', strtotime($end['date'])));

				$nextDate	= $startDate;

				while ($nextDate <= $endDate) {
					if ($nextDate <= $endDate && $this->day == date('w', $nextDate)) {
						$startDay	= date('Y-m-d '.$start['time'], $nextDate);
						$endDay		= date('Y-m-d '.$end['time'], $nextDate);

						$dates[] = array(
							'format_start'	=> $startDay,
							'format_end'	=> $endDay,
							'entire_day'	=> $this->isEntireDay(date('H:i:s', strtotime($startDay)), date('H:i:s', strtotime($endDay)))
						);
					}

					$nextDate = strtotime(date('Y-m-d 00:00:00', strtotime('Next '.$type, $nextDate)));
				}
			} else {
				$end = array(
					'date'	=> $this->isEmpty($end['date'], 'date', $start['date']),
					'time'	=> $this->isEmpty($end['time'], 'time', '23:59:59')
				);

				$startDate 	= strtotime(date('Y-m-d 00:00:00', strtotime($start['date'])));
				$endDate	= strtotime(date('Y-m-d 00:00:00', strtotime($end['date'])));

				$nextDate	= $startDate;

				while ($nextDate <= $endDate) {
					if ($nextDate <= $endDate) {
						$startDay 	= date('Y-m-d 00:00:00', $nextDate);
						$endDay		= date('Y-m-d 23:59:59', $nextDate);

						if ($nextDate == $startDate && $nextDate == $endDate) {
							$startDay 	= date('Y-m-d '.$start['time'], $nextDate);
							$endDay 	= date('Y-m-d '.$end['time'], $nextDate);
						} else if ($nextDate == $startDate) {
							$startDay 	= date('Y-m-d '.$start['time'], $nextDate);
						} else if ($nextDate == $endDate) {
							$endDay 	= date('Y-m-d '.$end['time'], $nextDate);
						}

						$dates[] = array(
							'format_start'	=> $startDay,
							'format_end'	=> $endDay,
							'entire_day'	=> $this->isEntireDay(date('H:i:s', strtotime($startDay)), date('H:i:s', strtotime($endDay)))
						);
					}

					$nextDate = strtotime('+1 day', $nextDate);
				}
			}

			return $dates;
		}

		/**
		 * @access public.
		 * @return String.
		 */
		public function toString() {
			$string = array();

			if ($this->is('day')) {
				$string[] = $this->xpdo->lexicon(strtolower($this->getDayOfWeek()));

				if ($this->isEntireDay()) {
					$string[] = $this->xpdo->lexicon('digitalsignage.schedule_time_format_entire_day');
				} else {
					$string[] = $this->xpdo->lexicon('digitalsignage.schedule_time_format_set', array(
						'start_time'	=> date($this->xpdo->getOption('manager_time_format'), strtotime($this->start_time)),
						'end_time'		=> date($this->xpdo->getOption('manager_time_format'), strtotime($this->end_time))
					));
				}
			} else {
				if ($this->isSingleDay()) {
					$string[] = date($this->xpdo->getOption('manager_date_format'), strtotime($this->start_date));

					if ($this->isEntireDay()) {
						$string[] = $this->xpdo->lexicon('digitalsignage.schedule_time_format_entire_day');
					} else {
						$string[] = $this->xpdo->lexicon('digitalsignage.schedule_time_format_set', array(
							'start_time'	=> date($this->xpdo->getOption('manager_time_format'), strtotime($this->start_time)),
							'end_time'		=> date($this->xpdo->getOption('manager_time_format'), strtotime($this->end_time))
						));
					}
				} else {
					if ($this->isEntireDay()) {
						$string[] = $this->xpdo->lexicon('digitalsignage.schedule_date_format_set', array(
							'start_date' 	=> date($this->xpdo->getOption('manager_date_format'), strtotime($this->start_date)),
							'end_date'		=> date($this->xpdo->getOption('manager_date_format'), strtotime($this->end_date))
						));

						$string[] = $this->xpdo->lexicon('digitalsignage.schedule_time_format_entire_day');
					} else {
						$string[] = $this->xpdo->lexicon('digitalsignage.schedule_date_format_set_long', array(
							'start_date'	=> date($this->xpdo->getOption('manager_date_format'), strtotime($this->start_date)),
							'end_date'		=> date($this->xpdo->getOption('manager_date_format'), strtotime($this->end_date)),
							'start_time'	=> date($this->xpdo->getOption('manager_time_format'), strtotime($this->start_time)),
							'end_time'		=> date($this->xpdo->getOption('manager_time_format'), strtotime($this->end_time))
						));
					}
				}
			}

			return implode(' ', $string);
		}
	}

?>
