<?php
	class TaskDB {
		const DB_NAME = 'tasks.db';
		private $_db;

		public function __construct() {
			$this->_db = new SQLiteDatabase(self::DB_NAME);
			//Если нет БД - создадим её
			if (filesize(self::DB_NAME)==0) {
				$sql = "CREATE TABLE tasks(
							id INTEGER PRIMARY KEY,
							name TEXT,
							status TEXT,
							project_id INTEGER,
							msg TEXT,
							priority INTEGER,
							deadline TEXT,
							deadlineTime TEXT)";
				$this->_db->query($sql);
				$sql = "CREATE TABLE projects(
							id INTEGER PRIMARY KEY,
							name TEXT)";
				$this->_db->query($sql);
			}
		}
		public function __destruct() {
			unset($this->_db);
		}
		public function clearData($data) {
			return sqlite_escape_string(trim(strip_tags($data)));
		}
		public function getMaxPriorityOfProject($id) {
			$id = (int)$id;
			$sql = "SELECT MAX(priority) AS 'max'
					FROM tasks
					WHERE project_id LIKE $id";
			$res = $this->_db->arrayQuery($sql, SQLITE_ASSOC);
			return $res['0']['max'];
		}
		public function saveProject($name, $id=NULL) {
			//проверяем нам пришла новый проект или отредактированный существующий
			if ($id) {
				$sql = "UPDATE projects
						SET name='$name'
						WHERE id LIKE $id";
			} else {
				$sql = "INSERT INTO projects(name)
						VALUES ('$name')";
			}
			try {
				$res = $this->_db->query($sql);
				if (!$res) {
					throw new SQLiteException(sqlite_error_string($this->_db->lastError()));
				}
				return true;
			} catch (SQLiteException $e) {
				return false;
			}
		}
		public function saveTask($name, $msg, $project, $deadline, $deadlineTime, $id=NULL, $priority=NULL) {
			//проверяем нам пришла новая задача или отредактированная существующая
			if ($id) {
				$sql = "UPDATE tasks
						SET name='$name', status='active', project_id=$project, msg='$msg', priority=$priority, deadline='$deadline', deadlineTime='$deadlineTime'
						WHERE id LIKE $id";
			} else {
				//получим максимально значение приоритета в данном проекте
				$priority = $this->getMaxPriorityOfProject($project)+1;
				$sql = "INSERT INTO tasks(name, status, project_id, msg, priority, deadline, deadlineTime)
						VALUES ('$name', 'active', $project, '$msg', $priority, '$deadline', '$deadlineTime')";
			}
			try {
				$res = $this->_db->query($sql);
				if(!$res) {
					throw new SQLiteException(sqlite_error_string($this->_db->lastError()));
				}
				return true;
			} catch (SQLiteException $e) {
				return false;
			}
		}
		public function getProjects() {
			$sql = "SELECT id, name
					FROM projects
					ORDER BY name ASC";
			$res = $this->_db->arrayQuery($sql, SQLITE_ASSOC);
			return $res;
		}
		public function getTasks($status, $projectId) {
			$projectId = (int)$projectId;
			$sql = "SELECT id, name, status, msg, priority, deadline, deadlineTime
					FROM tasks 
					WHERE project_id LIKE $projectId
					AND status LIKE '$status'
					ORDER BY priority ASC";
			$res = $this->_db->arrayQuery($sql, SQLITE_ASSOC);
			return $res;
		}
		function markDone($id) {
			$sql = "UPDATE tasks
					SET status='done'
					WHERE id LIKE $id";
			$res = $this->_db->query($sql);
			return true;
		}
		public function editTask($id) {
			$sql = "SELECT name, status, msg, priority, deadline, deadlineTime, project_id
					FROM tasks 
					WHERE id LIKE $id";
			$res = $this->_db->arrayQuery($sql, SQLITE_ASSOC);
			return $res[0];			
		}
		public function editProject($id) {
			$sql = "SELECT name
					FROM projects
					WHERE id LIKE $id";
			$res = $this->_db->arrayQuery($sql, SQLITE_ASSOC);
			return $res[0];
		}
		function priorityUp($id, $priority, $project_id) {
			$sql = "UPDATE tasks
					SET priority=priority+1
					WHERE priority LIKE $priority
					AND project_id LIKE $project_id";
			$res = $this->_db->query($sql);
			$sql = "UPDATE tasks
					SET priority=priority-1
					WHERE id LIKE $id";
			$res = $this->_db->query($sql);
			return true;
		}
		function priorityDown($id, $priority, $project_id) {
			$sql = "UPDATE tasks
					SET priority=priority-1
					WHERE priority LIKE $priority
					AND project_id LIKE $project_id";
			$res = $this->_db->query($sql);
			$sql = "UPDATE tasks
					SET priority=priority+1
					WHERE id LIKE $id";
			$res = $this->_db->query($sql);
			return true;
		}
		function deleteProject($id) {
			$sql = "DELETE FROM tasks
					WHERE project_id = $id";
			$res = $this->_db->query($sql);
			$sql = "DELETE FROM projects
					WHERE id = $id";
			$res = $this->_db->query($sql);
			return true;
		}
		function deleteTask($id) {
			$sql = "DELETE FROM tasks
					WHERE id = $id";
			$res = $this->_db->query($sql);
			return true;
		}
	}
?>