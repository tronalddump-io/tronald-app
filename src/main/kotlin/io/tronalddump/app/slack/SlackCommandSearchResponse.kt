package io.tronalddump.app.slack

import com.fasterxml.jackson.annotation.JsonProperty

data class SlackCommandSearchResponse(
        @JsonProperty("attachments")
        override val attachments: List<SlackCommandResponseAttachment> = emptyList(),

        @JsonProperty("icon_url")
        override val iconUrl: String? = "https://assets.tronalddump.io/img/tronalddump_150x150.png",

        @JsonProperty("mrkdwn")
        override val mrkdwn: Boolean? = true,

        @JsonProperty("text")
        override val text: String? = "*Available commands:*",

        @JsonProperty("response_type")
        override val responseType: SlackResponseType? = SlackResponseType.EPHEMERAL
) : SlackCommandResponse