package io.tronalddump.app.slack

import com.fasterxml.jackson.annotation.JsonProperty

enum class SlackResponseType {
    @JsonProperty("ephemeral")
    EPHEMERAL,

    @JsonProperty("in_channel")
    IN_CHANNEL
}
