<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace tool_dataflows\local\step;

use tool_dataflows\helper;

/**
 * Gets the files in a directory.
 *
 * @package   tool_dataflows
 * @author    Guillaume Barat <guillaumebarat@catalyst-au.net>
 * @copyright 2025, Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
trait sftp_rename_file_trait {
    use sftp_trait {
        sftp_trait::form_define_fields as  sftp_form_define_fields;
        sftp_trait::form_add_custom_inputs as sftp_form_add_custom_inputs;
        sftp_trait::validate_for_run as sftp_validate_for_run;
        sftp_trait::validate_config as sftp_validate_config;
    }

    /**
     * Return the definition of the fields available in this form.
     *
     * @param string $behaviour 'delete' or something else.
     * @return array
     */
    public static function form_define_fields($behaviour = 'rename'): array {
        $fields = self::sftp_form_define_fields('rename');
        return $fields;
    }

    /**
     * Custom elements for editing the step.
     *
     * @param \MoodleQuickForm $mform
     * @param string $behaviour default to the step.
     */
    public function form_add_custom_inputs(\MoodleQuickForm &$mform, $behaviour = 'rename') {
        $this->sftp_form_add_custom_inputs($mform, 'rename');
    }

    /**
     * Executes the step
     *
     * Performs an SFTP call according to config parameters.
     *
     * @param  mixed|null $input
     * @return mixed
     */
    public function execute($input = null) {
        $stepvars = $this->get_variables();
        $config = $stepvars->get('config');
        // At this point we need to disconnect once we are finished.
        try {
            // Skip if it is a dry run.
            if ($this->is_dry_run() && $this->has_side_effect()) {
                return $input;
            }

            $sftp = $this->init_sftp($config);

            $sourceisremote = helper::path_is_scheme($config->source, self::$sftpprefix);
            $targetisremote = helper::path_is_scheme($config->target, self::$sftpprefix);
            $sourcepath = $this->resolve_path($config->source);
            $targetpath = $this->resolve_path($config->target);
            // Rename from remote.
            if ($sourceisremote && $targetisremote) {
                $this->rename_from_remote($sftp, $sourcepath, $targetpath);
                return $input;
            }
            throw new \moodle_exception('connector_sftp:source_not_remote', 'tool_dataflows');
        } catch (\Throwable $e) {
            $this->enginestep->log->error($e->getMessage());
            if (isset($sftp)) {
                $sftp->disconnect();
            }
            throw new \moodle_exception($e->getMessage(), 'tool_dataflows');
        }
    }

    /**
     * Perform any extra validation that is required only for runs.
     *
     * @param string $behaviour
     * @return true|array Will return true or an array of errors.
     */
    public function validate_for_run($behaviour = 'rename') {
        $sftperrors = $this->sftp_validate_for_run('rename');
        if ($sftperrors === true) {
            $sftperrors = [];
        }
        return $sftperrors ?: true;
    }

    /**
     * Validate the configuration settings.
     *
     * @param object $config
     * @param string $behaviour
     * @return true|\lang_string[] true if valid, an array of errors otherwise
     */
    public function validate_config($config, $behaviour = 'rename') {
        $errors = $this->sftp_validate_config($config, 'rename');
        return $errors ?: true;
    }
}
