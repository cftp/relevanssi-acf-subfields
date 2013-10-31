=== Relevanssi: add ACF subfields to index ===
Contributors: cftp
Requires at least: 3.6.1
Tested up to: 3.6.1
Stable tag: 0.2

Finds subfields from ACF and feeds them to the Relevanssi indexer so they're findable in search.

== Description ==

Finds subfields from ACF and feeds them to the Relevanssi indexer so they're findable in search.

To have an ACF subfield, e.g. a Repeater, indexed, do this:

# Go to the Relevanssi options
# Scroll to "custom fields to index"
# Add your fields in the format `[field_name]_%_[subfield_name]`, e.g. "sections_%_section_text"
# Select "Build the Index", to recreate your Relevanssi index

== Changelog ==

= 0.1 =
* BUGFIX: Fix typo in hook

= 0.1 =
* First release
