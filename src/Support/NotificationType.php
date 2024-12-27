<?php

namespace Support;

enum NotificationType: string
{
	case success = 'success';
	case warning = 'warning';
	case error = 'error';
}
