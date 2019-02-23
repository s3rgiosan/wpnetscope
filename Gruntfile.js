/* global module require process */
module.exports = function(grunt) {
  var path = require("path");

  require("load-grunt-config")(grunt, {
    configPath: path.join(process.cwd(), "grunt/config"),
    jitGrunt: {
      customTasksDir: "grunt/tasks",
      staticMappings: {
        makepot: "grunt-wp-i18n"
      }
    },
    data: {
      i18n: {
        author: "Sérgio Santos",
        support: "https://s3rgiosan.com/",
        pluginSlug: "wpnetscope",
        mainFile: "wpnetscope",
        textDomain: "wpnetscope",
        potFilename: "wpnetscope"
      },
      badges: {
        packagist_stable:
          "[![Latest Stable Version](https://poser.pugx.org/s3rgiosan/wpnetscope/v/stable)](https://packagist.org/packages/s3rgiosan/wpnetscope)",
        packagist_downloads:
          "[![Total Downloads](https://poser.pugx.org/s3rgiosan/wpnetscope/downloads)](https://packagist.org/packages/s3rgiosan/wpnetscope)",
        packagist_license:
          "[![License](https://poser.pugx.org/s3rgiosan/wpnetscope/license)](https://packagist.org/packages/s3rgiosan/wpnetscope)",
        codacy_grade:
          "[![Codacy Badge](https://api.codacy.com/project/badge/Grade/696fcc2ec05b4dcba4810c37c7623a88)](https://www.codacy.com/app/s3rgiosan/wpnetscope?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=s3rgiosan/wpnetscope&amp;utm_campaign=Badge_Grade)",
        codeclimate_grade:
          "[![Code Climate](https://codeclimate.com/github/s3rgiosan/wpnetscope/badges/gpa.svg)](https://codeclimate.com/github/s3rgiosan/wpnetscope)"
      }
    }
  });
};
