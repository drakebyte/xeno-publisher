<?php

function pod_load( $pod_id ) {
	if ( is_numeric( $pod_id )) {
		$pod = DB::queryFirstRow( "SELECT * FROM %b WHERE pod_id=%i", 'pod', $pod_id );
		$podraw['fields'] = DB::query( "SELECT * FROM %b WHERE pod_id = %i AND lang_id = %i", 'podfields', $pod_id, 1 );
		foreach ($podraw['fields'] as $k => $v) {
			$pod['field'][$v['field_name']][] = $v['field_content'];
		}
	}
	return $pod;
}