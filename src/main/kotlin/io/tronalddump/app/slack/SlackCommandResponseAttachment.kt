package io.tronalddump.app.slack

data class SlackCommandResponseAttachment(
        val fallback: String? = null,
        val mrkdownIn: List<String>? = listOf("text"),
        val text: String? = null,
        val title: String? = null,
        val titleLink: String? = null
)
