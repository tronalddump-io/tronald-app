package io.tronalddump.app.slack

import com.fasterxml.jackson.annotation.JsonProperty

data class SlackCommandHelpResponse(
        @JsonProperty("attachments")
        override val attachments: List<SlackCommandResponseAttachment> = listOf(
                SlackCommandResponseAttachment(
                        title = "Get random Tronald trash",
                        text = "Type `/tronald` to get a random quote."
                ),
                SlackCommandResponseAttachment(
                        title = "Free text search",
                        text = "Type `/tronald ? {search_term}` to search The Tronald's dump."
                ),
                SlackCommandResponseAttachment(
                        title = "Help",
                        text = "Type `/tronald help` to display this help context."
                )
        ),

        @JsonProperty("icon_url")
        override val iconUrl: String? = "https://assets.tronalddump.io/img/tronalddump_150x150.png",

        @JsonProperty("mrkdwn")
        override val mrkdwn: Boolean? = true,

        @JsonProperty("text")
        override val text: String? = "*Available commands:*",

        @JsonProperty("response_type")
        override val responseType: SlackResponseType? = SlackResponseType.EPHEMERAL
) : SlackCommandResponse