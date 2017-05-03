<?php
	
	/**
	 * Narrowcasting
	 *
	 * Copyright 2016 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
	 *
	 * Narrowcasting is free software; you can redistribute it and/or modify it under
	 * the terms of the GNU General Public License as published by the Free Software
	 * Foundation; either version 2 of the License, or (at your option) any later
	 * version.
	 *
	 * Narrowcasting is distributed in the hope that it will be useful, but WITHOUT ANY
	 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
	 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along with
	 * Narrowcasting; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
	 * Suite 330, Boston, MA 02111-1307 USA
	 */
	 
	class NarrowcastingPlayersSchedules extends xPDOSimpleObject {
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
		 * @param Array $timestamp.
		 * @return Boolean.
		 */
		public function isScheduled($timestamp, $type) {
			if ($this->is('day')) {
				$date = $timestamp = date('Y-m-d H:i:s', strtotime(date($timestamp['date'].' '.$timestamp['time'])));
				
				if ('end' == $type) {
					if ($this->isEmpty(date('H:i:s', strtotime($timestamp)), 'time')) {
						$timestamp = date('Y-m-d H:i:s', strtotime($timestamp) + (60 * 60 * 24));
					}
				}

				if (date('w', strtotime($date)) == $this->day) {
					if (strtotime($this->getFirstDate($date)) < strtotime($timestamp) && strtotime($this->getLastDate($date)) > strtotime($timestamp)) {
						return true;	
					}
				}
			} else {
				
			}
			
			/*if (null === $timestamp) {
				$timestamp = time();
			} else if (is_string($timestamp)) {
				$timestamp = strtotime($timestamp);
			}
			
			if ($this->is('day')) {
				if (date('w', $timestamp) == $this->day) {
					if ($this->getStart($timestamp) <= $timestamp && $this->getEnd($timestamp) >= $timestamp) {
						return true;	
					}
				}
			} else {
				if ($this->getStart() <= $timestamp && $this->getEnd() >= $timestamp) {
					return true;	
				}
			}*/
			
			return false;
		}
		
		public function getFirstDate($timestamp) {
			return date('Y-m-d '.$this->start_time, strtotime($timestamp));
		}
		
		public function getLastDate($timestamp) {
			$timestamp = strtotime(date('Y-m-d '.$this->end_time, strtotime($timestamp)));
			
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
			return $this->isScheduled($start, 'start') || $this->isScheduled($end, 'end');
		}
		
		/**
		 * @access public.
		 * @param Integer $timestamp.
		 * @return Integer.
		 */
		/*public function getStart($timestamp = null) {
			if ($this->is('day')) {
				if (null === $timestamp) {
					$start = strtotime(date('Y-m-d '.$this->start_time));
				} else {
					$start = strtotime(date('Y-m-d '.$this->start_time, is_string($timestamp) ? strtotime($timestamp) : $timestamp));
				}
			} else {
				$start = strtotime(date($this->start_date.' '.$this->start_time));
			}
			
			return $start;
		}*/
		
		/**
		 * @access public.
		 * @param Integer $timestamp.
		 * @return Integer.
		 */
		/*public function getEnd($timestamp = null) {
			if ($this->is('day')) {
				if (null === $timestamp) {
					$end = strtotime(date('Y-m-d '.$this->end_time));
				} else {
					$end = strtotime(date('Y-m-d '.$this->end_time, is_string($timestamp) ? strtotime($timestamp) : $timestamp));
				}
			} else {
				if ($this->isEmpty($this->end_date, 'date')) {
					$end = strtotime(date($this->start_date.' '.$this->end_time));
				} else {
					$end = strtotime(date($this->end_date.' '.$this->end_time));
				}
			}
			
			if ($this->isEmpty($this->end_time, 'time')) {
				$end = $end + (60 * 60 * 24);
			}
			
			return $end;
		}*/

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
					$string[] = $this->xpdo->lexicon('narrowcasting.schedule_time_format_entire_day');
				} else {
					$string[] = $this->xpdo->lexicon('narrowcasting.schedule_time_format_set', array(
						'start_time'	=> date($this->xpdo->getOption('manager_time_format'), strtotime($this->start_time)),
						'end_time'		=> date($this->xpdo->getOption('manager_time_format'), strtotime($this->end_time))
					));
				}
			} else {
				if ($this->isSingleDay()) {
					$string[] = date($this->xpdo->getOption('manager_date_format'), strtotime($this->start_date));
					
					if ($this->isEntireDay()) {
						$string[] = $this->xpdo->lexicon('narrowcasting.schedule_time_format_entire_day');
					} else {
						$string[] = $this->xpdo->lexicon('narrowcasting.schedule_time_format_set', array(
							'start_time'	=> date($this->xpdo->getOption('manager_time_format'), strtotime($this->start_time)),
							'end_time'		=> date($this->xpdo->getOption('manager_time_format'), strtotime($this->end_time))
						));
					}
				} else {
					if ($this->isEntireDay()) {
						$string[] = $this->xpdo->lexicon('narrowcasting.schedule_date_format_set', array(
							'start_date' 	=> date($this->xpdo->getOption('manager_date_format'), strtotime($this->start_date)),
							'end_date'		=> date($this->xpdo->getOption('manager_date_format'), strtotime($this->end_date))
						));
						
						$string[] = $this->xpdo->lexicon('narrowcasting.schedule_time_format_entire_day');
					} else {
						$string[] = $this->xpdo->lexicon('narrowcasting.schedule_date_format_set_long', array(
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