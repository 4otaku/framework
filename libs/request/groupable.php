<?php

interface Request_Groupable
{
	public function can_group_with(Request $request);

	public function get_grouped_request($requests);
}