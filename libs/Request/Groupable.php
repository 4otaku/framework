<?php

namespace Otaku\Framework;

interface RequestGroupable
{
	public function can_group_with(Request $request);

	public function get_grouped_request($requests);
}