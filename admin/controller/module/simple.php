<?php
/*
@author  Dmitriy Kubarev
@link  http://www.simpleopencart.com
@link  http://www.opencart.com/index.php?route=extension/extension/info&extension_id=4811
*/  

class ControllerModuleSimple extends Controller {
    private $error = array(); 
    private $default_settings = 'YTo2OTp7czoxODoic2ltcGxlX2pvb21sYV9wYXRoIjtzOjA6IiI7czoxOToic2ltcGxlX2pvb21sYV9yb3V0ZSI7czowOiIiO3M6MTc6InNpbXBsZV9nZW9pcF9tb2RlIjtpOjE7czozODoic2ltcGxlX3JlZ2lzdHJhdGlvbl92aWV3X2N1c3RvbWVyX3R5cGUiO3M6MToiMCI7czozNDoic2ltcGxlX3JlZ2lzdHJhdGlvbl9zdWJzY3JpYmVfaW5pdCI7czoxOiIxIjtzOjI5OiJzaW1wbGVfcmVnaXN0cmF0aW9uX3N1YnNjcmliZSI7czoxOiIyIjtzOjI3OiJzaW1wbGVfcmVnaXN0cmF0aW9uX2NhcHRjaGEiO3M6MToiMCI7czoxODoic2ltcGxlX3VzZV9jb29raWVzIjtzOjE6IjAiO3M6Mjk6InNpbXBsZV9kaXNhYmxlX2d1ZXN0X2NoZWNrb3V0IjtzOjE6IjAiO3M6NDM6InNpbXBsZV9yZWdpc3RyYXRpb25fYWdyZWVtZW50X2NoZWNrYm94X2luaXQiO3M6MToiMCI7czozODoic2ltcGxlX3JlZ2lzdHJhdGlvbl9hZ3JlZW1lbnRfY2hlY2tib3giO3M6MToiMSI7czozMDoic2ltcGxlX3Nob3dfd2lsbF9iZV9yZWdpc3RlcmVkIjtzOjE6IjEiO3M6MjE6InNpbXBsZV9zZXRfZm9yX3JlbG9hZCI7czo2NzoibWFpbl9jb3VudHJ5X2lkLG1haW5fem9uZV9pZCxtYWluX2NpdHksbWFpbl9wb3N0Y29kZSxtYWluX2FkZHJlc3NfMSI7czozMjoic2ltcGxlX3JlZ2lzdHJhdGlvbl9hZ3JlZW1lbnRfaWQiO3M6MToiMCI7czozOToic2ltcGxlX3JlZ2lzdHJhdGlvbl9wYXNzd29yZF9sZW5ndGhfbWF4IjtzOjI6IjIwIjtzOjM5OiJzaW1wbGVfcmVnaXN0cmF0aW9uX3Bhc3N3b3JkX2xlbmd0aF9taW4iO3M6MToiNCI7czozNjoic2ltcGxlX3JlZ2lzdHJhdGlvbl9wYXNzd29yZF9jb25maXJtIjtzOjE6IjAiO3M6MzU6InNpbXBsZV9jaGVja291dF9hc2FwX2Zvcl9ub3RfbG9nZ2VkIjtzOjE6IjEiO3M6MzE6InNpbXBsZV9jaGVja291dF9hc2FwX2Zvcl9sb2dnZWQiO3M6MToiMSI7czoxODoic2ltcGxlX2ZpZWxkc19tYWluIjthOjE1OntzOjEwOiJtYWluX2VtYWlsIjthOjExOntzOjI6ImlkIjtzOjEwOiJtYWluX2VtYWlsIjtzOjU6ImxhYmVsIjthOjI6e3M6MjoicnUiO3M6NjoiRS1tYWlsIjtzOjI6ImVuIjtzOjY6IkUtbWFpbCI7fXM6NDoidHlwZSI7czo0OiJ0ZXh0IjtzOjY6InZhbHVlcyI7czowOiIiO3M6NDoiaW5pdCI7czowOiIiO3M6MTU6InZhbGlkYXRpb25fdHlwZSI7czoxOiIzIjtzOjE3OiJ2YWxpZGF0aW9uX3JlZ2V4cCI7czo1NjoiL15bYS16MC05X1wuXC1dezEsMjB9QFthLXowLTlcLlwtXXsxLDIwfVwuW2Etel17Miw0fSQvc2kiO3M6MTY6InZhbGlkYXRpb25fZXJyb3IiO2E6Mjp7czoyOiJydSI7czo2Mjoi0J7RiNC40LHQutCwINCyINCw0LTRgNC10YHQtSDRjdC70LXQutGC0YDQvtC90L3QvtC5INC/0L7Rh9GC0YsiO3M6MjoiZW4iO3M6NDM6IkUtTWFpbCBBZGRyZXNzIGRvZXMgbm90IGFwcGVhciB0byBiZSB2YWxpZCEiO31zOjc6InNhdmVfdG8iO3M6NToiZW1haWwiO3M6NDoibWFzayI7czowOiIiO3M6MTE6InBsYWNlaG9sZGVyIjthOjI6e3M6MjoicnUiO3M6MDoiIjtzOjI6ImVuIjtzOjA6IiI7fX1zOjE0OiJtYWluX2ZpcnN0bmFtZSI7YToxMzp7czoyOiJpZCI7czoxNDoibWFpbl9maXJzdG5hbWUiO3M6NToibGFiZWwiO2E6Mjp7czoyOiJydSI7czo2OiLQmNC80Y8iO3M6MjoiZW4iO3M6MTA6IkZpcnN0IE5hbWUiO31zOjQ6InR5cGUiO3M6NDoidGV4dCI7czo2OiJ2YWx1ZXMiO3M6MDoiIjtzOjQ6ImluaXQiO3M6MDoiIjtzOjE1OiJ2YWxpZGF0aW9uX3R5cGUiO3M6MToiMiI7czoxNDoidmFsaWRhdGlvbl9taW4iO3M6MToiMSI7czoxNDoidmFsaWRhdGlvbl9tYXgiO3M6MjoiMzAiO3M6MTc6InZhbGlkYXRpb25fcmVnZXhwIjtzOjA6IiI7czoxNjoidmFsaWRhdGlvbl9lcnJvciI7YToyOntzOjI6InJ1IjtzOjYwOiLQmNC80Y8g0LTQvtC70LbQvdC+INCx0YvRgtGMINC+0YIgMSDQtNC+IDMwINGB0LjQvNCy0L7Qu9C+0LIiO3M6MjoiZW4iO3M6NDc6IkZpcnN0IE5hbWUgbXVzdCBiZSBiZXR3ZWVuIDEgYW5kIDMyIGNoYXJhY3RlcnMhIjt9czo3OiJzYXZlX3RvIjtzOjk6ImZpcnN0bmFtZSI7czo0OiJtYXNrIjtzOjA6IiI7czoxMToicGxhY2Vob2xkZXIiO2E6Mjp7czoyOiJydSI7czowOiIiO3M6MjoiZW4iO3M6MDoiIjt9fXM6MTM6Im1haW5fbGFzdG5hbWUiO2E6MTM6e3M6MjoiaWQiO3M6MTM6Im1haW5fbGFzdG5hbWUiO3M6NToibGFiZWwiO2E6Mjp7czoyOiJydSI7czoxNDoi0KTQsNC80LjQu9C40Y8iO3M6MjoiZW4iO3M6OToiTGFzdCBOYW1lIjt9czo0OiJ0eXBlIjtzOjQ6InRleHQiO3M6NjoidmFsdWVzIjtzOjA6IiI7czo0OiJpbml0IjtzOjA6IiI7czoxNToidmFsaWRhdGlvbl90eXBlIjtzOjE6IjIiO3M6MTQ6InZhbGlkYXRpb25fbWluIjtzOjE6IjEiO3M6MTQ6InZhbGlkYXRpb25fbWF4IjtzOjI6IjMwIjtzOjE3OiJ2YWxpZGF0aW9uX3JlZ2V4cCI7czowOiIiO3M6MTY6InZhbGlkYXRpb25fZXJyb3IiO2E6Mjp7czoyOiJydSI7czo2ODoi0KTQsNC80LjQu9C40Y8g0LTQvtC70LbQvdC+INCx0YvRgtGMINC+0YIgMSDQtNC+IDMwINGB0LjQvNCy0L7Qu9C+0LIiO3M6MjoiZW4iO3M6NDY6Ikxhc3QgTmFtZSBtdXN0IGJlIGJldHdlZW4gMSBhbmQgMzIgY2hhcmFjdGVycyEiO31zOjc6InNhdmVfdG8iO3M6ODoibGFzdG5hbWUiO3M6NDoibWFzayI7czowOiIiO3M6MTE6InBsYWNlaG9sZGVyIjthOjI6e3M6MjoicnUiO3M6MDoiIjtzOjI6ImVuIjtzOjA6IiI7fX1zOjE0OiJtYWluX3RlbGVwaG9uZSI7YToxMzp7czoyOiJpZCI7czoxNDoibWFpbl90ZWxlcGhvbmUiO3M6NToibGFiZWwiO2E6Mjp7czoyOiJydSI7czoxNDoi0KLQtdC70LXRhNC+0L0iO3M6MjoiZW4iO3M6OToiVGVsZXBob25lIjt9czo0OiJ0eXBlIjtzOjQ6InRleHQiO3M6NjoidmFsdWVzIjtzOjA6IiI7czo0OiJpbml0IjtzOjA6IiI7czoxNToidmFsaWRhdGlvbl90eXBlIjtzOjE6IjIiO3M6MTQ6InZhbGlkYXRpb25fbWluIjtzOjE6IjMiO3M6MTQ6InZhbGlkYXRpb25fbWF4IjtzOjI6IjMyIjtzOjE3OiJ2YWxpZGF0aW9uX3JlZ2V4cCI7czowOiIiO3M6MTY6InZhbGlkYXRpb25fZXJyb3IiO2E6Mjp7czoyOiJydSI7czo2OToi0KLQtdC70LXRhNC+0L0g0LTQvtC70LbQtdC9INCx0YvRgtGMINC+0YIgMyDQtNC+IDMyINGB0LjQvNCy0L7Qu9C+0LIhIjtzOjI6ImVuIjtzOjQ2OiJUZWxlcGhvbmUgbXVzdCBiZSBiZXR3ZWVuIDMgYW5kIDMyIGNoYXJhY3RlcnMhIjt9czo3OiJzYXZlX3RvIjtzOjk6InRlbGVwaG9uZSI7czo0OiJtYXNrIjtzOjA6IiI7czoxMToicGxhY2Vob2xkZXIiO2E6Mjp7czoyOiJydSI7czowOiIiO3M6MjoiZW4iO3M6MDoiIjt9fXM6ODoibWFpbl9mYXgiO2E6MTM6e3M6MjoiaWQiO3M6ODoibWFpbl9mYXgiO3M6NToibGFiZWwiO2E6Mjp7czoyOiJydSI7czo4OiLQpNCw0LrRgSI7czoyOiJlbiI7czozOiJGYXgiO31zOjQ6InR5cGUiO3M6NDoidGV4dCI7czo2OiJ2YWx1ZXMiO3M6MDoiIjtzOjQ6ImluaXQiO3M6MDoiIjtzOjE1OiJ2YWxpZGF0aW9uX3R5cGUiO3M6MToiMCI7czoxNDoidmFsaWRhdGlvbl9taW4iO3M6MDoiIjtzOjE0OiJ2YWxpZGF0aW9uX21heCI7czowOiIiO3M6MTc6InZhbGlkYXRpb25fcmVnZXhwIjtzOjA6IiI7czoxNjoidmFsaWRhdGlvbl9lcnJvciI7YToyOntzOjI6InJ1IjtzOjA6IiI7czoyOiJlbiI7czowOiIiO31zOjc6InNhdmVfdG8iO3M6MzoiZmF4IjtzOjQ6Im1hc2siO3M6MDoiIjtzOjExOiJwbGFjZWhvbGRlciI7YToyOntzOjI6InJ1IjtzOjA6IiI7czoyOiJlbiI7czowOiIiO319czoxMjoibWFpbl9jb21wYW55IjthOjEzOntzOjI6ImlkIjtzOjEyOiJtYWluX2NvbXBhbnkiO3M6NToibGFiZWwiO2E6Mjp7czoyOiJydSI7czoxNjoi0JrQvtC80L/QsNC90LjRjyI7czoyOiJlbiI7czo3OiJDb21wYW55Ijt9czo0OiJ0eXBlIjtzOjQ6InRleHQiO3M6NjoidmFsdWVzIjtzOjA6IiI7czo0OiJpbml0IjtzOjA6IiI7czoxNToidmFsaWRhdGlvbl90eXBlIjtzOjE6IjAiO3M6MTQ6InZhbGlkYXRpb25fbWluIjtzOjA6IiI7czoxNDoidmFsaWRhdGlvbl9tYXgiO3M6MDoiIjtzOjE3OiJ2YWxpZGF0aW9uX3JlZ2V4cCI7czowOiIiO3M6MTY6InZhbGlkYXRpb25fZXJyb3IiO2E6Mjp7czoyOiJydSI7czowOiIiO3M6MjoiZW4iO3M6MDoiIjt9czo3OiJzYXZlX3RvIjtzOjc6ImNvbXBhbnkiO3M6NDoibWFzayI7czowOiIiO3M6MTE6InBsYWNlaG9sZGVyIjthOjI6e3M6MjoicnUiO3M6MDoiIjtzOjI6ImVuIjtzOjA6IiI7fX1zOjE1OiJtYWluX2NvbXBhbnlfaWQiO2E6MTM6e3M6MjoiaWQiO3M6MTU6Im1haW5fY29tcGFueV9pZCI7czo1OiJsYWJlbCI7YToyOntzOjI6InJ1IjtzOjEwOiJDb21wYW55IElEIjtzOjI6ImVuIjtzOjEwOiJDb21wYW55IElEIjt9czo0OiJ0eXBlIjtzOjQ6InRleHQiO3M6NjoidmFsdWVzIjtzOjA6IiI7czo0OiJpbml0IjtzOjA6IiI7czoxNToidmFsaWRhdGlvbl90eXBlIjtzOjE6IjAiO3M6MTQ6InZhbGlkYXRpb25fbWluIjtzOjA6IiI7czoxNDoidmFsaWRhdGlvbl9tYXgiO3M6MDoiIjtzOjE3OiJ2YWxpZGF0aW9uX3JlZ2V4cCI7czowOiIiO3M6MTY6InZhbGlkYXRpb25fZXJyb3IiO2E6Mjp7czoyOiJydSI7czowOiIiO3M6MjoiZW4iO3M6MDoiIjt9czo3OiJzYXZlX3RvIjtzOjEwOiJjb21wYW55X2lkIjtzOjQ6Im1hc2siO3M6MDoiIjtzOjExOiJwbGFjZWhvbGRlciI7YToyOntzOjI6InJ1IjtzOjA6IiI7czoyOiJlbiI7czowOiIiO319czoxMToibWFpbl90YXhfaWQiO2E6MTM6e3M6MjoiaWQiO3M6MTE6Im1haW5fdGF4X2lkIjtzOjU6ImxhYmVsIjthOjI6e3M6MjoicnUiO3M6NjoiVGF4IElEIjtzOjI6ImVuIjtzOjY6IlRheCBJRCI7fXM6NDoidHlwZSI7czo0OiJ0ZXh0IjtzOjY6InZhbHVlcyI7czowOiIiO3M6NDoiaW5pdCI7czowOiIiO3M6MTU6InZhbGlkYXRpb25fdHlwZSI7czoxOiIwIjtzOjE0OiJ2YWxpZGF0aW9uX21pbiI7czowOiIiO3M6MTQ6InZhbGlkYXRpb25fbWF4IjtzOjA6IiI7czoxNzoidmFsaWRhdGlvbl9yZWdleHAiO3M6MDoiIjtzOjE2OiJ2YWxpZGF0aW9uX2Vycm9yIjthOjI6e3M6MjoicnUiO3M6MDoiIjtzOjI6ImVuIjtzOjA6IiI7fXM6Nzoic2F2ZV90byI7czo2OiJ0YXhfaWQiO3M6NDoibWFzayI7czowOiIiO3M6MTE6InBsYWNlaG9sZGVyIjthOjI6e3M6MjoicnUiO3M6MDoiIjtzOjI6ImVuIjtzOjA6IiI7fX1zOjE0OiJtYWluX2FkZHJlc3NfMSI7YToxMzp7czoyOiJpZCI7czoxNDoibWFpbl9hZGRyZXNzXzEiO3M6NToibGFiZWwiO2E6Mjp7czoyOiJydSI7czoxMDoi0JDQtNGA0LXRgSI7czoyOiJlbiI7czoxNDoiQWRkcmVzcyBMaW5lIDEiO31zOjQ6InR5cGUiO3M6NDoidGV4dCI7czo2OiJ2YWx1ZXMiO3M6MDoiIjtzOjQ6ImluaXQiO3M6MDoiIjtzOjE1OiJ2YWxpZGF0aW9uX3R5cGUiO3M6MToiMiI7czoxNDoidmFsaWRhdGlvbl9taW4iO3M6MToiMyI7czoxNDoidmFsaWRhdGlvbl9tYXgiO3M6MzoiMTI4IjtzOjE3OiJ2YWxpZGF0aW9uX3JlZ2V4cCI7czowOiIiO3M6MTY6InZhbGlkYXRpb25fZXJyb3IiO2E6Mjp7czoyOiJydSI7czo2NToi0JDQtNGA0LXRgSDQtNC+0LvQttC10L0g0LHRi9GC0Ywg0L7RgiAzINC00L4gMTI4INGB0LjQvNCy0L7Qu9C+0LIiO3M6MjoiZW4iO3M6NDc6IkFkZHJlc3MgMSBtdXN0IGJlIGJldHdlZW4gMyBhbmQgMTI4IGNoYXJhY3RlcnMhIjt9czo3OiJzYXZlX3RvIjtzOjk6ImFkZHJlc3NfMSI7czo0OiJtYXNrIjtzOjA6IiI7czoxMToicGxhY2Vob2xkZXIiO2E6Mjp7czoyOiJydSI7czowOiIiO3M6MjoiZW4iO3M6MDoiIjt9fXM6MTQ6Im1haW5fYWRkcmVzc18yIjthOjEzOntzOjI6ImlkIjtzOjE0OiJtYWluX2FkZHJlc3NfMiI7czo1OiJsYWJlbCI7YToyOntzOjI6InJ1IjtzOjM1OiLQkNC00YDQtdGBICjQv9GA0L7QtNC+0LvQttC10L3QuNC1KSI7czoyOiJlbiI7czoxNDoiQWRkcmVzcyBMaW5lIDIiO31zOjQ6InR5cGUiO3M6NDoidGV4dCI7czo2OiJ2YWx1ZXMiO3M6MDoiIjtzOjQ6ImluaXQiO3M6MDoiIjtzOjE1OiJ2YWxpZGF0aW9uX3R5cGUiO3M6MToiMCI7czoxNDoidmFsaWRhdGlvbl9taW4iO3M6MDoiIjtzOjE0OiJ2YWxpZGF0aW9uX21heCI7czowOiIiO3M6MTc6InZhbGlkYXRpb25fcmVnZXhwIjtzOjA6IiI7czoxNjoidmFsaWRhdGlvbl9lcnJvciI7YToyOntzOjI6InJ1IjtzOjA6IiI7czoyOiJlbiI7czowOiIiO31zOjc6InNhdmVfdG8iO3M6OToiYWRkcmVzc18yIjtzOjQ6Im1hc2siO3M6MDoiIjtzOjExOiJwbGFjZWhvbGRlciI7YToyOntzOjI6InJ1IjtzOjA6IiI7czoyOiJlbiI7czowOiIiO319czoxMzoibWFpbl9wb3N0Y29kZSI7YToxMzp7czoyOiJpZCI7czoxMzoibWFpbl9wb3N0Y29kZSI7czo1OiJsYWJlbCI7YToyOntzOjI6InJ1IjtzOjEyOiLQmNC90LTQtdC60YEiO3M6MjoiZW4iO3M6ODoiUG9zdGNvZGUiO31zOjQ6InR5cGUiO3M6NDoidGV4dCI7czo2OiJ2YWx1ZXMiO3M6MDoiIjtzOjQ6ImluaXQiO3M6MDoiIjtzOjE1OiJ2YWxpZGF0aW9uX3R5cGUiO3M6MToiMiI7czoxNDoidmFsaWRhdGlvbl9taW4iO3M6MToiMiI7czoxNDoidmFsaWRhdGlvbl9tYXgiO3M6MjoiMTAiO3M6MTc6InZhbGlkYXRpb25fcmVnZXhwIjtzOjA6IiI7czoxNjoidmFsaWRhdGlvbl9lcnJvciI7YToyOntzOjI6InJ1IjtzOjgzOiLQn9C+0YfRgtC+0LLRi9C5INC40L3QtNC10LrRgSDQtNC+0LvQttC10L0g0LHRi9GC0Ywg0L7RgiAyINC00L4gMTAg0YHQuNC80LLQvtC70L7QsiI7czoyOiJlbiI7czo0NToiUG9zdGNvZGUgbXVzdCBiZSBiZXR3ZWVuIDIgYW5kIDEwIGNoYXJhY3RlcnMhIjt9czo3OiJzYXZlX3RvIjtzOjg6InBvc3Rjb2RlIjtzOjQ6Im1hc2siO3M6MDoiIjtzOjExOiJwbGFjZWhvbGRlciI7YToyOntzOjI6InJ1IjtzOjA6IiI7czoyOiJlbiI7czowOiIiO319czoxNToibWFpbl9jb3VudHJ5X2lkIjthOjEzOntzOjI6ImlkIjtzOjE1OiJtYWluX2NvdW50cnlfaWQiO3M6NToibGFiZWwiO2E6Mjp7czoyOiJydSI7czoxMjoi0KHRgtGA0LDQvdCwIjtzOjI6ImVuIjtzOjc6IkNvdW50cnkiO31zOjQ6InR5cGUiO3M6Njoic2VsZWN0IjtzOjY6InZhbHVlcyI7czo5OiJjb3VudHJpZXMiO3M6NDoiaW5pdCI7czoxOiIwIjtzOjE0OiJ2YWxpZGF0aW9uX21pbiI7czowOiIiO3M6MTQ6InZhbGlkYXRpb25fbWF4IjtzOjA6IiI7czoxNzoidmFsaWRhdGlvbl9yZWdleHAiO3M6MDoiIjtzOjE1OiJ2YWxpZGF0aW9uX3R5cGUiO3M6MToiNCI7czoxNjoidmFsaWRhdGlvbl9lcnJvciI7YToyOntzOjI6InJ1IjtzOjUwOiLQn9C+0LbQsNC70YPQudGB0YLQsCDQstGL0LHQtdGA0LjRgtC1INGB0YLRgNCw0L3RgyI7czoyOiJlbiI7czoyMzoiUGxlYXNlIHNlbGVjdCBhIGNvdW50cnkiO31zOjc6InNhdmVfdG8iO3M6MTA6ImNvdW50cnlfaWQiO3M6NDoibWFzayI7czowOiIiO3M6MTE6InBsYWNlaG9sZGVyIjthOjI6e3M6MjoicnUiO3M6MDoiIjtzOjI6ImVuIjtzOjA6IiI7fX1zOjEyOiJtYWluX3pvbmVfaWQiO2E6MTM6e3M6MjoiaWQiO3M6MTI6Im1haW5fem9uZV9pZCI7czo1OiJsYWJlbCI7YToyOntzOjI6InJ1IjtzOjEyOiLQoNC10LPQuNC+0L0iO3M6MjoiZW4iO3M6NDoiWm9uZSI7fXM6NDoidHlwZSI7czo2OiJzZWxlY3QiO3M6NjoidmFsdWVzIjtzOjU6InpvbmVzIjtzOjQ6ImluaXQiO3M6MToiMCI7czoxNDoidmFsaWRhdGlvbl9taW4iO3M6MDoiIjtzOjE0OiJ2YWxpZGF0aW9uX21heCI7czowOiIiO3M6MTc6InZhbGlkYXRpb25fcmVnZXhwIjtzOjA6IiI7czoxNToidmFsaWRhdGlvbl90eXBlIjtzOjE6IjQiO3M6MTY6InZhbGlkYXRpb25fZXJyb3IiO2E6Mjp7czoyOiJydSI7czo1MDoi0J/QvtC20LDQu9GD0LnRgdGC0LAg0LLRi9Cx0LXRgNC40YLQtSDRgNC10LPQuNC+0L0iO3M6MjoiZW4iO3M6MjI6IlBsZWFzZSBzZWxlY3QgYSByZWdpb24iO31zOjc6InNhdmVfdG8iO3M6Nzoiem9uZV9pZCI7czo0OiJtYXNrIjtzOjA6IiI7czoxMToicGxhY2Vob2xkZXIiO2E6Mjp7czoyOiJydSI7czowOiIiO3M6MjoiZW4iO3M6MDoiIjt9fXM6OToibWFpbl9jaXR5IjthOjEzOntzOjI6ImlkIjtzOjk6Im1haW5fY2l0eSI7czo1OiJsYWJlbCI7YToyOntzOjI6InJ1IjtzOjEwOiLQk9C+0YDQvtC0IjtzOjI6ImVuIjtzOjQ6IkNpdHkiO31zOjQ6InR5cGUiO3M6NDoidGV4dCI7czo2OiJ2YWx1ZXMiO3M6MDoiIjtzOjQ6ImluaXQiO3M6MDoiIjtzOjE1OiJ2YWxpZGF0aW9uX3R5cGUiO3M6MToiMiI7czoxNDoidmFsaWRhdGlvbl9taW4iO3M6MToiMiI7czoxNDoidmFsaWRhdGlvbl9tYXgiO3M6MzoiMTI4IjtzOjE3OiJ2YWxpZGF0aW9uX3JlZ2V4cCI7czowOiIiO3M6MTY6InZhbGlkYXRpb25fZXJyb3IiO2E6Mjp7czoyOiJydSI7czo2NToi0JPQvtGA0L7QtCDQtNC+0LvQttC10L0g0LHRi9GC0Ywg0L7RgiAyINC00L4gMTI4INGB0LjQvNCy0L7Qu9C+0LIiO3M6MjoiZW4iO3M6NDI6IkNpdHkgbXVzdCBiZSBiZXR3ZWVuIDIgYW5kIDEyOCBjaGFyYWN0ZXJzISI7fXM6Nzoic2F2ZV90byI7czo0OiJjaXR5IjtzOjQ6Im1hc2siO3M6MDoiIjtzOjExOiJwbGFjZWhvbGRlciI7YToyOntzOjI6InJ1IjtzOjA6IiI7czoyOiJlbiI7czowOiIiO319czoxMjoibWFpbl9jb21tZW50IjthOjEzOntzOjI6ImlkIjtzOjEyOiJtYWluX2NvbW1lbnQiO3M6NToibGFiZWwiO2E6Mjp7czoyOiJydSI7czoyMjoi0JrQvtC80LzQtdC90YLQsNGA0LjQuSI7czoyOiJlbiI7czo3OiJDb21tZW50Ijt9czo0OiJ0eXBlIjtzOjg6InRleHRhcmVhIjtzOjY6InZhbHVlcyI7czowOiIiO3M6NDoiaW5pdCI7czowOiIiO3M6MTU6InZhbGlkYXRpb25fdHlwZSI7czoxOiIwIjtzOjE0OiJ2YWxpZGF0aW9uX21pbiI7czowOiIiO3M6MTQ6InZhbGlkYXRpb25fbWF4IjtzOjA6IiI7czoxNzoidmFsaWRhdGlvbl9yZWdleHAiO3M6MDoiIjtzOjE2OiJ2YWxpZGF0aW9uX2Vycm9yIjthOjI6e3M6MjoicnUiO3M6MDoiIjtzOjI6ImVuIjtzOjA6IiI7fXM6Nzoic2F2ZV90byI7czo3OiJjb21tZW50IjtzOjQ6Im1hc2siO3M6MDoiIjtzOjExOiJwbGFjZWhvbGRlciI7YToyOntzOjI6InJ1IjtzOjA6IiI7czoyOiJlbiI7czowOiIiO319fXM6MTQ6InNpbXBsZV9oZWFkZXJzIjthOjI6e3M6MTE6ImhlYWRlcl9tYWluIjthOjI6e3M6MjoiaWQiO3M6MTE6ImhlYWRlcl9tYWluIjtzOjU6ImxhYmVsIjthOjI6e3M6MjoicnUiO3M6Mzc6ItCe0YHQvdC+0LLQvdCw0Y8g0LjQvdGE0L7RgNC80LDRhtC40Y8iO3M6MjoiZW4iO3M6OToiTWFpbiBpbmZvIjt9fXM6MTQ6ImhlYWRlcl9hZGRyZXNzIjthOjI6e3M6MjoiaWQiO3M6MTQ6ImhlYWRlcl9hZGRyZXNzIjtzOjU6ImxhYmVsIjthOjI6e3M6MjoicnUiO3M6MTA6ItCQ0LTRgNC10YEiO3M6MjoiZW4iO3M6NzoiQWRkcmVzcyI7fX19czoxNzoic2ltcGxlX2hlYWRlcl90YWciO3M6MjoiaDMiO3M6MzU6InNpbXBsZV9jdXN0b21lcl92aWV3X2FkZHJlc3Nfc2VsZWN0IjtzOjE6IjEiO3M6MzQ6InNpbXBsZV9jdXN0b21lcl92aWV3X2N1c3RvbWVyX3R5cGUiO3M6MToiMCI7czozMzoic2ltcGxlX2FjY291bnRfdmlld19jdXN0b21lcl90eXBlIjtzOjE6IjAiO3M6Mjg6InNpbXBsZV9zZXRfY2hlY2tvdXRfY3VzdG9tZXIiO2E6MTp7czo1OiJncm91cCI7YToxOntpOjE7czoxNzQ6ImhlYWRlcl9tYWluLG1haW5fZW1haWwsbWFpbl9maXJzdG5hbWUsbWFpbl9sYXN0bmFtZSxtYWluX3RlbGVwaG9uZSxzcGxpdF9zcGxpdCxoZWFkZXJfYWRkcmVzcyxtYWluX2NvdW50cnlfaWQsbWFpbl96b25lX2lkLG1haW5fY2l0eSxtYWluX3Bvc3Rjb2RlLG1haW5fYWRkcmVzc18xLG1haW5fY29tbWVudCI7fX1zOjI3OiJzaW1wbGVfc2V0X2NoZWNrb3V0X2FkZHJlc3MiO2E6MTp7czo1OiJncm91cCI7YToxOntpOjE7czo5NjoibWFpbl9maXJzdG5hbWUsbWFpbl9sYXN0bmFtZSxtYWluX2NvdW50cnlfaWQsbWFpbl96b25lX2lkLG1haW5fY2l0eSxtYWluX3Bvc3Rjb2RlLG1haW5fYWRkcmVzc18xIjt9fXM6MjM6InNpbXBsZV9zZXRfcmVnaXN0cmF0aW9uIjthOjE6e3M6NToiZ3JvdXAiO2E6MTp7aToxO3M6MTM1OiJoZWFkZXJfbWFpbixtYWluX2VtYWlsLG1haW5fZmlyc3RuYW1lLG1haW5fbGFzdG5hbWUsbWFpbl90ZWxlcGhvbmUsaGVhZGVyX2FkZHJlc3MsbWFpbl9jb3VudHJ5X2lkLG1haW5fem9uZV9pZCxtYWluX2NpdHksbWFpbl9hZGRyZXNzXzEiO319czoyMzoic2ltcGxlX3NldF9hY2NvdW50X2luZm8iO2E6MTp7czo1OiJncm91cCI7YToxOntpOjE7czo2MzoibWFpbl9lbWFpbCxtYWluX2ZpcnN0bmFtZSxtYWluX2xhc3RuYW1lLG1haW5fdGVsZXBob25lLG1haW5fZmF4Ijt9fXM6MjY6InNpbXBsZV9zZXRfYWNjb3VudF9hZGRyZXNzIjthOjE6e3M6NToiZ3JvdXAiO2E6MTp7aToxO3M6OTY6Im1haW5fZmlyc3RuYW1lLG1haW5fbGFzdG5hbWUsbWFpbl9jb3VudHJ5X2lkLG1haW5fem9uZV9pZCxtYWluX2NpdHksbWFpbl9wb3N0Y29kZSxtYWluX2FkZHJlc3NfMSI7fX1zOjI4OiJzaW1wbGVfc2hvd19zaGlwcGluZ19hZGRyZXNzIjtzOjE6IjEiO3M6Mzg6InNpbXBsZV9zaG93X3NoaXBwaW5nX2FkZHJlc3Nfc2FtZV9pbml0IjtzOjE6IjEiO3M6Mzg6InNpbXBsZV9zaG93X3NoaXBwaW5nX2FkZHJlc3Nfc2FtZV9zaG93IjtzOjE6IjEiO3M6MzU6InNpbXBsZV9zaGlwcGluZ192aWV3X2FkZHJlc3Nfc2VsZWN0IjtzOjE6IjEiO3M6MjY6InNpbXBsZV9jdXN0b21lcl92aWV3X2xvZ2luIjtzOjE6IjEiO3M6NDQ6InNpbXBsZV9jdXN0b21lcl92aWV3X2N1c3RvbWVyX3N1YnNjcmliZV9pbml0IjtzOjE6IjEiO3M6MzI6InNpbXBsZV9jdXN0b21lcl9hY3Rpb25fc3Vic2NyaWJlIjtzOjE6IjIiO3M6NDA6InNpbXBsZV9jdXN0b21lcl92aWV3X3Bhc3N3b3JkX2xlbmd0aF9tYXgiO3M6MjoiMjAiO3M6NDA6InNpbXBsZV9jdXN0b21lcl92aWV3X3Bhc3N3b3JkX2xlbmd0aF9taW4iO3M6MToiNCI7czozNzoic2ltcGxlX2N1c3RvbWVyX3ZpZXdfcGFzc3dvcmRfY29uZmlybSI7czoxOiIwIjtzOjMzOiJzaW1wbGVfY3VzdG9tZXJfZ2VuZXJhdGVfcGFzc3dvcmQiO3M6MToiMCI7czoyNjoic2ltcGxlX2N1c3RvbWVyX3ZpZXdfZW1haWwiO3M6MToiMiI7czo0Mzoic2ltcGxlX2N1c3RvbWVyX3ZpZXdfY3VzdG9tZXJfcmVnaXN0ZXJfaW5pdCI7czoxOiIwIjtzOjMxOiJzaW1wbGVfY3VzdG9tZXJfYWN0aW9uX3JlZ2lzdGVyIjtzOjE6IjIiO3M6MzA6InNpbXBsZV9jdXN0b21lcl9oaWRlX2lmX2xvZ2dlZCI7czoxOiIwIjtzOjM2OiJzaW1wbGVfcGF5bWVudF92aWV3X2F1dG9zZWxlY3RfZmlyc3QiO3M6MToiMCI7czozMzoic2ltcGxlX3BheW1lbnRfdmlld19hZGRyZXNzX2VtcHR5IjtzOjE6IjEiO3M6Mjc6InNpbXBsZV9wYXltZW50X21ldGhvZHNfaGlkZSI7czoxOiIwIjtzOjM3OiJzaW1wbGVfc2hpcHBpbmdfdmlld19hdXRvc2VsZWN0X2ZpcnN0IjtzOjE6IjAiO3M6MzQ6InNpbXBsZV9zaGlwcGluZ192aWV3X2FkZHJlc3NfZW1wdHkiO3M6MToiMSI7czoyNjoic2ltcGxlX3NoaXBwaW5nX3ZpZXdfdGl0bGUiO3M6MToiMSI7czoyODoic2ltcGxlX3NoaXBwaW5nX21ldGhvZHNfaGlkZSI7czoxOiIwIjtzOjI4OiJzaW1wbGVfY29tbW9uX3ZpZXdfaGVscF90ZXh0IjtzOjE6IjAiO3M6MjY6InNpbXBsZV9jb21tb25fdmlld19oZWxwX2lkIjtzOjE6IjAiO3M6NDI6InNpbXBsZV9jb21tb25fdmlld19hZ3JlZW1lbnRfY2hlY2tib3hfaW5pdCI7czoxOiIwIjtzOjM3OiJzaW1wbGVfY29tbW9uX3ZpZXdfYWdyZWVtZW50X2NoZWNrYm94IjtzOjE6IjAiO3M6MzM6InNpbXBsZV9jb21tb25fdmlld19hZ3JlZW1lbnRfdGV4dCI7czoxOiIwIjtzOjMxOiJzaW1wbGVfY29tbW9uX3ZpZXdfYWdyZWVtZW50X2lkIjtzOjE6IjAiO3M6MTc6InNpbXBsZV9tYXhfd2VpZ2h0IjtzOjA6IiI7czoxNzoic2ltcGxlX21pbl93ZWlnaHQiO3M6MDoiIjtzOjE5OiJzaW1wbGVfbWF4X3F1YW50aXR5IjtzOjA6IiI7czoxOToic2ltcGxlX21pbl9xdWFudGl0eSI7czowOiIiO3M6MTc6InNpbXBsZV9tYXhfYW1vdW50IjtzOjA6IiI7czoxNzoic2ltcGxlX21pbl9hbW91bnQiO3M6MDoiIjtzOjE4OiJzaW1wbGVfc2hvd193ZWlnaHQiO3M6MToiMCI7czoxNjoic2ltcGxlX3VzZV90b3RhbCI7czoxOiIwIjtzOjE4OiJzaW1wbGVfZW1wdHlfZW1haWwiO3M6MDoiIjtzOjEyOiJzaW1wbGVfZGVidWciO3M6MToiMCI7czoyMjoic2ltcGxlX2NvbW1vbl90ZW1wbGF0ZSI7czoxMjI6IntoZWxwfXtsZWZ0X2NvbHVtbn17Y2FydH17Y3VzdG9tZXJ9ey9sZWZ0X2NvbHVtbn17cmlnaHRfY29sdW1ufXtzaGlwcGluZ317cGF5bWVudH17YWdyZWVtZW50fXsvcmlnaHRfY29sdW1ufXtwYXltZW50X2Zvcm19Ijt9';
    private $settings;
    private $store_settings;

    public function install() {
        $this->load->model('setting/setting');
        $this->load->model('setting/store');

        $stores = $this->model_setting_store->getStores();
        $stores[] = array('store_id' => 0, 'name' => 'default');

        $settings = unserialize(base64_decode($this->default_settings));

        foreach ($stores as $key => $value) {
            $this->model_setting_setting->editSetting('simple', $settings, $value['store_id']);
        }

        $this->create_tables();
    }

    private function create_tables() {
        $this->db->query('CREATE TABLE IF NOT EXISTS `simple_custom_data` (
                          `object_type` tinyint(4) NOT NULL,
                          `object_id` int(11) NOT NULL,
                          `customer_id` int(11) NOT NULL,
                          `data` text NOT NULL,
                          PRIMARY KEY (`object_type`,`object_id`,`customer_id`)
                        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
    }
      
    private function load_language($path) {
        $language = $this->language;
        if (isset($language) && method_exists($language, 'load')) {
            $this->language->load($path);
            unset($language);
            return;
        }

        $load = $this->load;
        if (isset($load) && method_exists($load, 'language')) {
            $this->load->language($path);
            unset($load);
            return;
        }
    }
    public function index() {
        $this->load_language('module/simple');
            
        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('setting/setting');
        $this->load->model('setting/store');
        
        $this->data['stores'] = $this->model_setting_store->getStores();
        $this->data['stores'][] = array('store_id' => 0, 'name' => 'default');

        if (isset($this->request->get['store_id'])) {
            $this->data['store_id'] = $this->request->get['store_id'];
        } else {
            $this->data['store_id'] = 0;
        }

        $this->load->model('sale/customer_group');
        $this->data['groups'] = $this->model_sale_customer_group->getCustomerGroups();
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->create_tables();
            
            if (is_uploaded_file($this->request->files['import']['tmp_name'])) {
                $content = file_get_contents($this->request->files['import']['tmp_name']);
                $tmp = unserialize(base64_decode($content));
                if (is_array($tmp)) {
                    $this->request->post = $tmp;
                }
            }

            foreach ($this->data['groups'] as $group) {
             
                if (isset($this->request->post['simple_set_checkout_customer']['shipping'][$group['customer_group_id']]) && is_array($this->request->post['simple_set_checkout_customer']['shipping'][$group['customer_group_id']])) {
                    $tmp = array();
                    foreach ($this->request->post['simple_set_checkout_customer']['shipping'][$group['customer_group_id']] as $key => $value) {
                        $tmp[str_replace('_101_', '.', $key)] = $value;
                    }
                    $this->request->post['simple_set_checkout_customer']['shipping'][$group['customer_group_id']] = $tmp;
                }

                if (isset($this->request->post['simple_set_checkout_address']['shipping'][$group['customer_group_id']]) && is_array($this->request->post['simple_set_checkout_address']['shipping'][$group['customer_group_id']])) {
                    $tmp = array();
                    foreach ($this->request->post['simple_set_checkout_address']['shipping'][$group['customer_group_id']] as $key => $value) {
                        $tmp[str_replace('_101_', '.', $key)] = $value;
                    }
                    $this->request->post['simple_set_checkout_address']['shipping'][$group['customer_group_id']] = $tmp;
                }
            }

            $simple_common_template = $this->request->post['simple_common_template'];
            $simple_common_template = str_replace(' ', '', $simple_common_template);

            $find = array(
                '{cart}',
                '{shipping}',
                '{payment}',
                '{agreement}',
                '{help}',
                '{payment_form}'
            );  

            $replace = array(
                '{cart}'         => '',
                '{shipping}'     => '',
                '{payment}'      => '',
                '{agreement}'    => '',
                '{help}'         => '',
                '{payment_form}' => ''
            );  

            $simple_common_template = trim(str_replace($find, $replace, $simple_common_template));
            $simple_common_template = preg_replace('/\[\[[-_a-zA-Z0-9.]+\]\]/si', '', $simple_common_template);

            $find = array(
                    '{left_column}{/left_column}',
                    '{right_column}{/right_column}'
            );  

            $replace = array(
                '{left_column}{/left_column}'   => '',
                '{right_column}{/right_column}' => ''
            );
                
            $simple_common_template = trim(str_replace($find, $replace, $simple_common_template));

            if ($simple_common_template == '{customer}') {
                $this->request->post['simple_customer_two_column'] = true;
            } else {
                $this->request->post['simple_customer_two_column'] = false;
            }

            $this->model_setting_setting->editSetting('simple', $this->request->post, $this->data['store_id']);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('module/simple', 'token=' . $this->session->data['token'] . '&store_id=' . $this->data['store_id'], 'SSL'));
        }

            
        $this->data['heading_title'] = $this->language->get('heading_title');

        if (isset($this->session->data['success'])) {
            $this->data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $this->data['success'] = '';
        }
      
        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('module/simple', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );

        $this->data['button_save']    = $this->language->get('button_save');
        $this->data['button_cancel']  = $this->language->get('button_cancel');
        $this->data['button_restore'] = $this->language->get('button_restore');

        $this->data['action_without_store'] = $this->url->link('module/simple', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['action']               = $this->url->link('module/simple', 'token=' . $this->session->data['token'] . '&store_id=' . $this->data['store_id'], 'SSL');
        $this->data['backup_link']          = $this->url->link('module/simple/backup', 'token=' . $this->session->data['token'] . '&store_id=' . $this->data['store_id'], 'SSL');
        $this->data['restore_link']         = $this->url->link('module/simple/restore', 'token=' . $this->session->data['token'] . '&store_id=' . $this->data['store_id'], 'SSL');
        $this->data['header_save_link']     = $this->url->link('module/simple/header', 'token=' . $this->session->data['token'] . '&store_id=' . $this->data['store_id'], 'SSL');
        $this->data['footer_save_link']     = $this->url->link('module/simple/footer', 'token=' . $this->session->data['token'] . '&store_id=' . $this->data['store_id'], 'SSL');
        $this->data['cancel']               = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['token']                = $this->session->data['token'];
        
        $this->data['tab_checkout']                               = $this->language->get('tab_checkout');
        $this->data['tab_customer_fields']                        = $this->language->get('tab_customer_fields');
        $this->data['tab_registration']                           = $this->language->get('tab_registration');
        $this->data['tab_account_pages']                          = $this->language->get('tab_account_pages');
        $this->data['tab_backup']                                 = $this->language->get('tab_backup');
        $this->data['tab_headers']                                = $this->language->get('tab_headers');
        $this->data['tab_methods']                                = $this->language->get('tab_methods');
        
        $this->data['text_yes']                                   = $this->language->get('text_yes');
        $this->data['text_no']                                    = $this->language->get('text_no');
        
        $this->data['text_module_links']                          = $this->language->get('text_module_links');
        $this->data['entry_payment_module']                       = $this->language->get('entry_payment_module');
        $this->data['entry_shipping_modules']                     = $this->language->get('entry_shipping_modules');
        $this->data['text_select_shipping']                       = $this->language->get('text_select_shipping');
        $this->data['entry_template']                             = $this->language->get('entry_template');
        $this->data['entry_template_description']                 = $this->language->get('entry_template_description');
        $this->data['text_simplecheckout']                        = $this->language->get('text_simplecheckout');
        $this->data['text_agreement_block']                       = $this->language->get('text_agreement_block');
        $this->data['entry_agreement_id']                         = $this->language->get('entry_agreement_id');
        $this->data['entry_agreement_text']                       = $this->language->get('entry_agreement_text');
        $this->data['entry_agreement_checkbox']                   = $this->language->get('entry_agreement_checkbox');
        $this->data['entry_agreement_checkbox_init']              = $this->language->get('entry_agreement_checkbox_init');
        $this->data['text_shipping_block']                        = $this->language->get('text_shipping_block');
        $this->data['entry_shipping_title']                       = $this->language->get('text_shipping_title');
        $this->data['entry_shipping_address_empty']               = $this->language->get('entry_shipping_address_empty');
        $this->data['text_payment_block']                         = $this->language->get('text_payment_block');
        $this->data['entry_payment_address_empty']                = $this->language->get('entry_payment_address_empty');
        $this->data['text_customer_block']                        = $this->language->get('text_customer_block');
        $this->data['entry_customer_register']                    = $this->language->get('entry_customer_register');
        $this->data['text_user_choice']                           = $this->language->get('text_user_choice');
        $this->data['entry_customer_login']                       = $this->language->get('entry_customer_login');
        $this->data['entry_customer_type']                        = $this->language->get('entry_customer_type');
        $this->data['entry_customer_type_selection']              = $this->language->get('entry_customer_type_selection');
        $this->data['entry_customer_group_after_reg']             = $this->language->get('entry_customer_group_after_reg');
        $this->data['entry_customer_register_init']               = $this->language->get('entry_customer_register_init');
        $this->data['entry_customer_address_select']              = $this->language->get('entry_customer_address_select');
        $this->data['text_registration_page']                     = $this->language->get('text_registration_page');
        $this->data['entry_registration_agreement_id']            = $this->language->get('entry_registration_agreement_id');
        $this->data['entry_registration_agreement_checkbox']      = $this->language->get('entry_registration_agreement_checkbox');
        $this->data['entry_registration_agreement_checkbox_init'] = $this->language->get('entry_registration_agreement_checkbox_init');
        $this->data['entry_registration_captcha']                 = $this->language->get('entry_registration_captcha');
        $this->data['text_fields_default']                        = $this->language->get('text_fields_default');
        $this->data['entry_field_id']                             = $this->language->get('entry_field_id');
        $this->data['entry_field_label']                          = $this->language->get('entry_field_label');
        $this->data['entry_field_type']                           = $this->language->get('entry_field_type');
        $this->data['entry_field_init']                           = $this->language->get('entry_field_init');
        $this->data['entry_field_values']                         = $this->language->get('entry_field_values');
        $this->data['entry_field_validation']                     = $this->language->get('entry_field_validation');
        $this->data['entry_field_save_to']                        = $this->language->get('entry_field_save_to');
        $this->data['entry_shipping_address_full']                = $this->language->get('entry_shipping_address_full');
        $this->data['entry_payment_address_full']                 = $this->language->get('entry_payment_address_full');
        $this->data['entry_shipping_address_full_description']    = $this->language->get('entry_shipping_address_full_description');
        $this->data['entry_payment_address_full_description']     = $this->language->get('entry_payment_address_full_description');
        $this->data['text_validation_none']                       = $this->language->get('text_validation_none');
        $this->data['text_validation_length']                     = $this->language->get('text_validation_length');
        $this->data['text_validation_regexp']                     = $this->language->get('text_validation_regexp');
        $this->data['text_validation_values']                     = $this->language->get('text_validation_values');
        $this->data['text_validation_not_null']                   = $this->language->get('text_validation_not_null');
        $this->data['entry_field_validation_error']               = $this->language->get('entry_field_validation_error');
        $this->data['text_select']                                = $this->language->get('text_select');
        $this->data['entry_customer_subscribe']                   = $this->language->get('entry_customer_subscribe');
        $this->data['entry_customer_subscribe_init']              = $this->language->get('entry_customer_subscribe_init');
        $this->data['text_add_field']                             = $this->language->get('text_add_field');
        $this->data['button_add']                                 = $this->language->get('button_add');
        $this->data['button_delete']                              = $this->language->get('button_delete');
        $this->data['entry_customer_password_confirm']            = $this->language->get('entry_customer_password_confirm');
        $this->data['entry_geoip_init']                           = $this->language->get('entry_geoip_init');
        $this->data['entry_shipping_module']                      = $this->language->get('entry_shipping_module');
        $this->data['entry_customer_fields']                      = $this->language->get('entry_customer_fields');
        $this->data['entry_password_length']                      = $this->language->get('entry_password_length');
        $this->data['entry_customer_email_field']                 = $this->language->get('entry_customer_email_field');
        $this->data['text_hide']                                  = $this->language->get('text_hide');
        $this->data['text_show_not_required']                     = $this->language->get('text_show_not_required');
        $this->data['text_required']                              = $this->language->get('text_required');
        $this->data['text_validation_function']                   = $this->language->get('text_validation_function');
        $this->data['entry_payment_autoselect_first']             = $this->language->get('entry_payment_autoselect_first');
        $this->data['entry_shipping_autoselect_first']            = $this->language->get('entry_shipping_autoselect_first');
        $this->data['entry_jquery_masked_input_mask']             = $this->language->get('entry_jquery_masked_input_mask');
        $this->data['entry_city_autocomplete']                    = $this->language->get('entry_city_autocomplete');
        $this->data['entry_generate_password']                    = $this->language->get('entry_generate_password');
        $this->data['entry_placeholder']                          = $this->language->get('entry_placeholder');
        $this->data['entry_save_label']                           = $this->language->get('entry_save_label');
        $this->data['text_order_minmax']                          = $this->language->get('text_order_minmax');
        $this->data['entry_use_total']                            = $this->language->get('entry_use_total');
        $this->data['entry_min_amount']                           = $this->language->get('entry_min_amount');
        $this->data['entry_max_amount']                           = $this->language->get('entry_max_amount');
        $this->data['entry_min_quantity']                         = $this->language->get('entry_min_quantity');
        $this->data['entry_max_quantity']                         = $this->language->get('entry_max_quantity');
        $this->data['entry_min_weight']                           = $this->language->get('entry_min_weight');
        $this->data['entry_max_weight']                           = $this->language->get('entry_max_weight');
        $this->data['entry_payment_method']                       = $this->language->get('entry_payment_method');
        $this->data['text_shipping_address_block']                = $this->language->get('text_shipping_address_block');
        $this->data['entry_shipping_address_select']              = $this->language->get('entry_shipping_address_select');
        $this->data['entry_shipping_address_show']                = $this->language->get('entry_shipping_address_show');
        $this->data['entry_shipping_address_same_init']           = $this->language->get('entry_shipping_address_same_init');
        $this->data['entry_shipping_address_same_show']           = $this->language->get('entry_shipping_address_same_show');
        $this->data['entry_shipping_address_fields']              = $this->language->get('entry_shipping_address_fields');
        $this->data['entry_help_text']                            = $this->language->get('entry_help_text');
        $this->data['entry_help_id']                              = $this->language->get('entry_help_id');
        $this->data['text_help_block']                            = $this->language->get('text_help_block');
        $this->data['entry_hide']                                 = $this->language->get('entry_hide');
        $this->data['entry_hide_if_logged']                       = $this->language->get('entry_hide_if_logged');
        $this->data['entry_empty_email']                          = $this->language->get('entry_empty_email');
        $this->data['entry_show_weight']                          = $this->language->get('entry_show_weight');
        $this->data['entry_fields_for_reload']                    = $this->language->get('entry_fields_for_reload');
        $this->data['entry_use_cookies']                          = $this->language->get('entry_use_cookies');
        $this->data['entry_show_will_be_registerd']               = $this->language->get('entry_show_will_be_registerd');
        $this->data['entry_guest_checkout']                       = $this->language->get('entry_guest_checkout');
        $this->data['entry_field_object']                         = $this->language->get('entry_field_object');
        $this->data['text_account_info_page']                     = $this->language->get('text_account_info_page');
        $this->data['text_account_address_page']                  = $this->language->get('text_account_address_page');
        $this->data['text_restore']                               = $this->language->get('text_restore');             
        $this->data['text_checkout_asap_logged']                  = $this->language->get('text_checkout_asap_logged');             
        $this->data['text_checkout_asap_not_logged']              = $this->language->get('text_checkout_asap_not_logged');             
        $this->data['text_no_only_after_click']                   = $this->language->get('text_no_only_after_click');             
        $this->data['text_payment_options_for_groups']            = $this->language->get('text_payment_options_for_groups');             
        $this->data['text_shipping_options_for_groups']           = $this->language->get('text_shipping_options_for_groups');             
        $this->data['entry_googleapi']                            = $this->language->get('entry_googleapi');             
        $this->data['entry_googleapi_key']                        = $this->language->get('entry_googleapi_key');             
        $this->data['entry_googleapi_language']                   = $this->language->get('entry_googleapi_language');             
        $this->data['entry_confirm_email']                        = $this->language->get('entry_confirm_email');             
        $this->data['entry_field_place']                          = $this->language->get('entry_field_place');             
        $this->data['entry_field_object_field']                   = $this->language->get('entry_field_object_field');             
        $this->data['entry_steps']                                = $this->language->get('entry_steps');             
        $this->data['entry_steps_summary']                        = $this->language->get('entry_steps_summary');             
        $this->data['entry_comment_target']                       = $this->language->get('entry_comment_target');             
        $this->data['entry_minify']                               = $this->language->get('entry_minify');             

        $this->init_field('simple_links', array());
        $this->init_field('simple_common_template', '{left_column}{cart}{customer}{/left_column}{right_column}{shipping}{payment}{agreement}{/right_column}');
        $this->init_field('simple_common_view_agreement_id');
        $this->init_field('simple_common_view_agreement_text');
        $this->init_field('simple_common_view_agreement_checkbox');
        $this->init_field('simple_common_view_agreement_checkbox_init');
        $this->init_field('simple_shipping_view_title');
        $this->init_field('simple_shipping_view_address_empty');
        $this->init_field('simple_payment_view_address_empty');
        $this->init_field('simple_payment_view_autoselect_first');
        $this->init_field('simple_shipping_view_autoselect_first');
        $this->init_field('simple_customer_action_register');
        $this->init_field('simple_customer_view_login');
        $this->init_field('simple_customer_view_customer_type');
        $this->init_field('simple_customer_view_customer_register_init');
        $this->init_field('simple_customer_view_address_select');
        $this->init_field('simple_registration_agreement_id');
        $this->init_field('simple_registration_agreement_checkbox');
        $this->init_field('simple_registration_agreement_checkbox_init');
        $this->init_field('simple_registration_captcha');
        $this->init_field('simple_shipping_view_address_full', array());
        $this->init_field('simple_payment_view_address_full', array());
        $this->init_field('simple_customer_action_subscribe');
        $this->init_field('simple_customer_view_customer_subscribe_init');
        $this->init_field('simple_registration_subscribe');
        $this->init_field('simple_registration_subscribe_init');
        $this->init_field('simple_customer_view_password_confirm');
        $this->init_field('simple_customer_view_password_length_min');
        $this->init_field('simple_customer_view_password_length_max');
        $this->init_field('simple_registration_password_confirm');
        $this->init_field('simple_registration_password_length_min');
        $this->init_field('simple_registration_password_length_max');
        $this->init_field('simple_registration_view_customer_type');

        $this->init_field('simple_set_checkout_customer');
        $this->init_field('simple_set_checkout_address');
        $this->init_field('simple_set_registration');
        $this->init_field('simple_set_account_info');
        $this->init_field('simple_set_account_address');

        $this->init_field('simple_customer_view_email');
        $this->init_field('simple_customer_view_email_confirm');
        $this->init_field('simple_customer_generate_password');
        $this->init_field('simple_registration_generate_password');
        $this->init_field('simple_registration_view_email_confirm');
        $this->init_field('simple_show_shipping_address');
        $this->init_field('simple_show_shipping_address_same_init', 1);
        $this->init_field('simple_show_shipping_address_same_show', 1);
        $this->init_field('simple_shipping_view_address_select');
        $this->init_field('simple_use_total');
        $this->init_field('simple_min_amount');
        $this->init_field('simple_max_amount');
        $this->init_field('simple_min_quantity');
        $this->init_field('simple_max_quantity');
        $this->init_field('simple_min_weight');
        $this->init_field('simple_max_weight');
        $this->init_field('simple_debug');
        $this->init_field('simple_common_view_help_id');
        $this->init_field('simple_common_view_help_text');
        $this->init_field('simple_joomla_path');
        $this->init_field('simple_joomla_route');
        $this->init_field('simple_shipping_methods_hide');
        $this->init_field('simple_payment_methods_hide');
        $this->init_field('simple_customer_hide_if_logged');
        $this->init_field('simple_empty_email');
        $this->init_field('simple_show_weight');
        $this->init_field('simple_set_for_reload');
        $this->init_field('simple_use_cookies');
        $this->init_field('simple_show_will_be_registered');
        $this->init_field('simple_disable_guest_checkout');
        $this->init_field('simple_checkout_asap_for_not_logged');
        $this->init_field('simple_checkout_asap_for_logged');
        $this->init_field('simple_group_shipping');
        $this->init_field('simple_group_payment');
        $this->init_field('simple_account_view_customer_type');
        $this->init_field('simple_headers', array());
        $this->init_field('simple_header_tag', 'h3');
        $this->init_field('simple_geoip_mode', '1');
        $this->init_field('simple_googleapi');
        $this->init_field('simple_googleapi_key');
        $this->init_field('simple_show_back');
        $this->init_field('simple_shipping_titles', array());
        $this->init_field('simple_payment_titles', array());
        $this->init_field('simple_type_of_selection_of_group', 'radio');
        $this->init_field('simple_customer_group_id_after_reg', $this->config->get('config_customer_group_id'));
        $this->init_field('simple_steps');
        $this->init_field('simple_steps_summary');
        $this->init_field('simple_comment_target');
        $this->init_field('simple_minify', 0);
        
        // fix
        $this->data['tab_methods'] = $this->data['tab_methods'] == 'tab_methods' ? 'Stubs for methods' : $this->data['tab_methods'];

        $this->load->model('setting/extension');

        $fields_for_autoreloading_needed = false;
        
        $payment_extensions = $this->model_setting_extension->getInstalled('payment');
        $tmp = array();
        foreach ($payment_extensions as $extension) {
            if ($this->config->get($extension . '_status')) {
                $tmp[] = $extension;
            }
            if ($this->config->get($extension . '_geo_zone_id')) {
                $fields_for_autoreloading_needed = true;
            }
        }
        $payment_extensions = $tmp;
        
        $this->data['payment_extensions'] = array();
        
        $files = glob(DIR_APPLICATION . 'controller/payment/*.php');
    
        if ($files) {
            foreach ($files as $file) {
                $extension = basename($file, '.php');

                if (in_array($extension, $payment_extensions)) {
                    $this->load_language('payment/' . $extension);
                    $this->data['payment_extensions'][$extension] = $this->language->get('heading_title');
                }
            }
        }
      
        $shipping_extensions = $this->model_setting_extension->getInstalled('shipping');
        $tmp = array();
        foreach ($shipping_extensions as $extension) {
            if ($this->config->get($extension . '_status')) {
                $tmp[] = $extension;
            }
            if ($this->config->get($extension . '_geo_zone_id')) {
                $fields_for_autoreloading_needed = true;
            }
        }
        $shipping_extensions = $tmp;
        
        $this->data['shipping_extensions'] = array();
        
        $files = glob(DIR_APPLICATION . 'controller/shipping/*.php');
    
        if ($files) {
            foreach ($files as $file) {
                $extension = basename($file, '.php');

                if (in_array($extension, $shipping_extensions)) {
                    $this->load_language('shipping/' . $extension);
                    $this->data['shipping_extensions'][$extension] = $this->language->get('heading_title');
                }
            }
        }

        if (!$fields_for_autoreloading_needed) {
        //    $this->data['simple_set_for_reload'] = '';
        }
        
        $shipping_codes = array_keys($this->data['shipping_extensions']);
        
        $customer_group_id = $this->config->get('config_customer_group_id');

        $this->data['shipping_extensions_for_customer'] = array_diff(isset($this->data['simple_set_checkout_customer']['shipping'][$customer_group_id]) ? array_keys($this->data['simple_set_checkout_customer']['shipping'][$customer_group_id]) : array(), $shipping_codes);
        $this->data['shipping_extensions_for_shipping_address'] = array_diff(isset($this->data['simple_set_checkout_address']['shipping'][$customer_group_id]) ? array_keys($this->data['simple_set_checkout_address']['shipping'][$customer_group_id]) : array(), $shipping_codes);
        
        $this->load->model('localisation/language');
        $languages = $this->model_localisation_language->getLanguages();
        $this->data['languages'] = array();
        foreach ($languages as $language) {
            $language['code'] = str_replace('-', '_', strtolower($language['code']));
            $this->data['languages'][] = $language;
        }

        $current_language = strtolower($this->config->get('config_admin_language'));
        $this->data['current_language'] = $current_language;

        $this->load->model('catalog/information');
        $this->data['informations'] = $this->model_catalog_information->getInformations();
        
        $this->init_field('simple_fields_main', array());
        $this->init_field('simple_fields_custom', array());
        
        $this->data['simple_fields_all'] = $this->data['simple_fields_main'] + $this->data['simple_fields_custom'];

        $address_fields = array('main_firstname','main_lastname','main_company','main_company_id','main_tax_id','main_address_1','main_address_2','main_city','main_postcode','main_zone_id','main_country_id');
        $account_info_fields = array('main_email','main_firstname','main_lastname','main_fax','main_telephone');

        $this->data['simple_fields_for_checkout_customer'] = array();
        $this->data['simple_fields_for_checkout_shipping_address'] = array();
        $this->data['simple_fields_for_registration'] = array();
        $this->data['simple_fields_for_account_info'] = array();
        $this->data['simple_fields_for_account_address'] = array();

        foreach ($this->data['simple_fields_main'] as $settings) {
            $key = $settings['id'];
            $text = !empty($settings['label'][$current_language]) ? $settings['label'][$current_language] : $key;

            $this->data['simple_fields_for_checkout_customer'][$key] = $text;

            if (in_array($key, $address_fields)) {
                $this->data['simple_fields_for_checkout_shipping_address'][$key] = $text;
            }

            if (in_array($key, $address_fields) || in_array($key, $account_info_fields)) {
                $this->data['simple_fields_for_registration'][$key] = $text;
            }

            if (in_array($key, $account_info_fields)) {
                $this->data['simple_fields_for_account_info'][$key] = $text;
            }

            if (in_array($key, $address_fields)) {
                $this->data['simple_fields_for_account_address'][$key] = $text;
            }
        }

        $headers = array();
        foreach ($this->data['simple_headers'] as $settings) {
            $key = $settings['id'];
            $text = !empty($settings['label'][$current_language]) ? $settings['label'][$current_language] : $key;
            $headers[$key] = $text;
        }

        foreach ($this->data['simple_fields_custom'] as $settings) {
            $key = $settings['id'];
            $text = !empty($settings['label'][$current_language]) ? $settings['label'][$current_language] : $key;
            $this->data['simple_fields_for_checkout_customer'][$key] = $text;

            if ($settings['object_type'] == 'address' || $settings['object_type'] == 'order') {
                $this->data['simple_fields_for_checkout_shipping_address'][$key] = $text;
            }

            if ($settings['object_type'] == 'address' || $settings['object_type'] == 'customer') {
                $this->data['simple_fields_for_registration'][$key] = $text;
            }

            if ($settings['object_type'] == 'customer') {
                $this->data['simple_fields_for_account_info'][$key] = $text;
            }

            if ($settings['object_type'] == 'address') {
                $this->data['simple_fields_for_account_address'][$key] = $text;
            }
        }

        $this->data['simple_fields_for_registration'] += $headers;
        $this->data['simple_fields_for_checkout_customer'] += $headers + array('split_split' => '<-- split on two columns -->');
        $this->data['simple_fields_for_checkout_shipping_address'] += $headers + array('split_split' => '<-- split on two columns -->');
        $this->data['simple_fields_for_account_info'] += $headers;
        $this->data['simple_fields_for_account_address'] += $headers;

        foreach ($this->data['groups'] as $group) {
            if (empty($this->data['simple_set_checkout_customer']['group'][$group['customer_group_id']]) && !empty($this->data['simple_set_checkout_customer']['group'][1])) {
                $this->data['simple_set_checkout_customer']['group'][$group['customer_group_id']] = $this->data['simple_set_checkout_customer']['group'][1];
            }
            if (empty($this->data['simple_set_checkout_address']['group'][$group['customer_group_id']]) && !empty($this->data['simple_set_checkout_address']['group'][1])) {
                $this->data['simple_set_checkout_address']['group'][$group['customer_group_id']] = $this->data['simple_set_checkout_address']['group'][1];
            }
            if (empty($this->data['simple_set_registration']['group'][$group['customer_group_id']]) && !empty($this->data['simple_set_registration']['group'][1])) {
                $this->data['simple_set_registration']['group'][$group['customer_group_id']] = $this->data['simple_set_registration']['group'][1];
            }
            if (empty($this->data['simple_set_account_info']['group'][$group['customer_group_id']]) && !empty($this->data['simple_set_account_info']['group'][1])) {
                $this->data['simple_set_account_info']['group'][$group['customer_group_id']] = $this->data['simple_set_account_info']['group'][1];
            }
            if (empty($this->data['simple_set_account_address']['group'][$group['customer_group_id']]) && !empty($this->data['simple_set_account_address']['group'][1])) {
                $this->data['simple_set_account_address']['group'][$group['customer_group_id']] = $this->data['simple_set_account_address']['group'][1];
            }
        }

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }
        
        $this->load->model('localisation/country');
        $this->data['countries'] = $this->model_localisation_country->getCountries();
        
        $this->load->model('localisation/zone');
        $this->data['zones'] = isset($this->data['simple_fields_main']['main_country_id']['init']) ? $this->model_localisation_zone->getZonesByCountryId($this->data['simple_fields_main']['main_country_id']['init']) : array();
        
        $this->data['zone_action'] = $this->url->link('module/simple/zone', 'token=' . $this->session->data['token'], 'SSL');

        $header_content = '';
        $footer_content = '';
        if (file_exists(DIR_CATALOG . 'view/theme/' . $this->get_setting('config_template') . '/template/account/forgotten.tpl')) {
            $tpl = file_get_contents(DIR_CATALOG . 'view/theme/' . $this->get_setting('config_template') . '/template/account/forgotten.tpl');
            $f_b = utf8_strpos($tpl, '<form');
            $header_content = utf8_substr($tpl, 0, $f_b);
            $f_e = utf8_strpos($tpl, '</form>');
            $footer_content = utf8_substr($tpl, $f_e + 7);;
        }
        
        $this->data['theme_folder'] = DIR_CATALOG . 'view/theme/' . $this->get_setting('config_template') . '/';
        $this->data['styles_path'] = DIR_CATALOG . 'view/theme/' . $this->get_setting('config_template') . '/stylesheet/simple.css';
        $this->data['common_header_path'] = DIR_CATALOG . 'view/theme/' . $this->get_setting('config_template') . '/template/common/simple_header.tpl';
        $this->data['common_header_content'] = $header_content;
        $this->data['common_footer_path'] = DIR_CATALOG . 'view/theme/' . $this->get_setting('config_template') . '/template/common/simple_footer.tpl';
        $this->data['common_footer_content'] = $footer_content;

        $this->template = 'module/simple.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }
    
    private function init_field($field_name, $default_value = '') {
        if (empty($this->settings)) {
            $this->settings = $this->model_setting_setting->getSetting('simple',$this->data['store_id']);
            if (empty($this->settings)) {
                $this->settings = unserialize(base64_decode($this->default_settings));
            }
        }

        if (isset($this->request->post[$field_name])) {
            $this->data[$field_name] = $this->request->post[$field_name];
        } elseif (isset($this->settings[$field_name])) {
            if ($this->is_serialized($this->settings[$field_name])) {
                $this->settings[$field_name] = unserialize($this->settings[$field_name]);
            }
            $this->data[$field_name] = $this->settings[$field_name];
        }

        if (!isset($this->data[$field_name])) {
            $this->data[$field_name] = $default_value;
        }
    }

    private function get_setting($key) {
        if (empty($this->store_settings)) {
            $this->store_settings = $this->model_setting_setting->getSetting('config', $this->data['store_id']);
        }
        
        return isset($this->store_settings[$key]) ? $this->store_settings[$key] : null;
    }

    private function is_serialized( $data ) {
        // if it isn't a string, it isn't serialized
        if ( !is_string( $data ) )
            return false;
        $data = trim( $data );
        if ( 'N;' == $data )
            return true;
        if ( !preg_match( '/^([adObis]):/', $data, $badions ) )
            return false;
        switch ( $badions[1] ) {
            case 'a' :
            case 'O' :
            case 's' :
                if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
                    return true;
                break;
            case 'b' :
            case 'i' :
            case 'd' :
                if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
                    return true;
                break;
        }
        return false;
    }
    
    public function zone() {
        $output = '<option value="">' . $this->language->get('text_select') . '</option>';

        $this->load->model('localisation/zone');

        $results = $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']);
        
        foreach ($results as $result) {
            $output .= '<option value="' . $result['zone_id'] . '">' . $result['name'] . '</option>';
        } 

        if (!$results) {
            $output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
        }

        $this->response->setOutput($output);
    }  

    private function validate() {
        if (!$this->user->hasPermission('modify', 'module/simple')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (empty($this->request->post['simple_common_template'])) {
            $this->error['warning'] = $this->language->get('error_exists');
            $this->error['error_simple_common_template'] = $this->data['error_simple_common_template'] = $this->language->get('error_simple_common_template');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }  
    }

    public function custom() {

        $this->load->model('tool/simplecustom');

        $this->data['action'] = $this->url->link('module/simple/custom', 'token=' . $this->session->data['token'] . '&set=' . $this->request->get['set'] . '&type=' . $this->request->get['type'] . '&id=' . $this->request->get['id'], 'SSL');

        if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
            $this->model_tool_simplecustom->updateData($this->request->get['type'], $this->request->get['id'], $this->request->get['set'], $this->request->post);
        }
        
        $this->data['button_save'] = $this->language->get('button_save');

        $this->data['custom'] = $this->model_tool_simplecustom->loadData($this->request->get['type'], $this->request->get['id'], $this->request->get['set']);
        $this->data['form_id'] = $this->request->get['type'].'_'.$this->request->get['set'].'_'.$this->request->get['id'];

        $this->template = 'module/simple_custom.tpl';
        $this->response->setOutput($this->render());
    }

    public function backup() {
        $this->load->model('setting/setting');
        $this->load->model('setting/store');

        if (isset($this->request->get['store_id'])) {
            $this->data['store_id'] = $this->request->get['store_id'];
        } else {
            $this->data['store_id'] = 0;
        }

        if (empty($this->settings)) {
            $this->settings = $this->model_setting_setting->getSetting('simple',$this->data['store_id']);
            if (empty($this->settings)) {
                $this->settings = unserialize(base64_decode($this->default_settings));
            }
        }

        $this->response->addheader('Pragma: public');
        $this->response->addheader('Expires: 0');
        $this->response->addheader('Content-Description: File Transfer');
        $this->response->addheader('Content-Type: application/octet-stream');
        $this->response->addheader('Content-Disposition: attachment; filename=' . 'simple_settings_for_store_' . $this->data['store_id'] . '.txt');
        $this->response->addheader('Content-Transfer-Encoding: binary');
        
        $this->response->setOutput(base64_encode(serialize($this->settings)));
    }

    public function header() {
        $this->load->model('setting/setting');
        $this->load->model('setting/store');

        if (isset($this->request->get['store_id'])) {
            $this->data['store_id'] = $this->request->get['store_id'];
        } else {
            $this->data['store_id'] = 0;
        }

        $header_content = '';
        if (file_exists(DIR_CATALOG . 'view/theme/' . $this->get_setting('config_template') . '/template/account/forgotten.tpl')) {
            $tpl = file_get_contents(DIR_CATALOG . 'view/theme/' . $this->get_setting('config_template') . '/template/account/forgotten.tpl');
            $f_b = utf8_strpos($tpl, '<form');
            $header_content = utf8_substr($tpl, 0, $f_b);
        }

        $this->response->addheader('Pragma: public');
        $this->response->addheader('Expires: 0');
        $this->response->addheader('Content-Description: File Transfer');
        $this->response->addheader('Content-Type: application/octet-stream');
        $this->response->addheader('Content-Disposition: attachment; filename=' . 'simple_header.tpl');
        $this->response->addheader('Content-Transfer-Encoding: binary');
        
        $this->response->setOutput($header_content);
    }

    public function footer() {
        $this->load->model('setting/setting');
        $this->load->model('setting/store');

        if (isset($this->request->get['store_id'])) {
            $this->data['store_id'] = $this->request->get['store_id'];
        } else {
            $this->data['store_id'] = 0;
        }

        $footer_content = '';
        if (file_exists(DIR_CATALOG . 'view/theme/' . $this->get_setting('config_template') . '/template/account/forgotten.tpl')) {
            $tpl = file_get_contents(DIR_CATALOG . 'view/theme/' . $this->get_setting('config_template') . '/template/account/forgotten.tpl');
            $f_e = utf8_strpos($tpl, '</form>');
            $footer_content = utf8_substr($tpl, $f_e + 7);
        }
        
        $this->response->addheader('Pragma: public');
        $this->response->addheader('Expires: 0');
        $this->response->addheader('Content-Description: File Transfer');
        $this->response->addheader('Content-Type: application/octet-stream');
        $this->response->addheader('Content-Disposition: attachment; filename=' . 'simple_footer.tpl');
        $this->response->addheader('Content-Transfer-Encoding: binary');
        
        $this->response->setOutput($footer_content);
    }
}
?>