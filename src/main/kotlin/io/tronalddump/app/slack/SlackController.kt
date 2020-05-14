package io.tronalddump.app.slack

import io.swagger.v3.oas.annotations.Operation
import io.tronalddump.app.Url
import io.tronalddump.app.quote.QuoteEntity
import io.tronalddump.app.quote.QuoteRepository
import org.springframework.data.domain.Page
import org.springframework.data.domain.PageRequest
import org.springframework.data.domain.Pageable
import org.springframework.http.HttpHeaders
import org.springframework.http.HttpStatus
import org.springframework.http.MediaType
import org.springframework.web.bind.annotation.RequestMapping
import org.springframework.web.bind.annotation.RequestMethod
import org.springframework.web.bind.annotation.RequestParam
import org.springframework.web.bind.annotation.RestController
import org.springframework.web.servlet.ModelAndView
import java.util.*
import java.util.regex.Matcher
import java.util.regex.Pattern

@RestController
class SlackController(
        private val quoteRepository: QuoteRepository,
        private val slackService: SlackService
) {

    private val COMMAND: String = "tronald"
    private val PAGE_SIZE: Int = 5

    @Operation(hidden = true)
    @RequestMapping(
            headers = ["${HttpHeaders.ACCEPT}=${MediaType.TEXT_HTML_VALUE}"],
            method = [RequestMethod.GET],
            produces = [MediaType.TEXT_HTML_VALUE],
            value = [Url.SLACK_CONNECT]
    )
    fun connect(
            @RequestParam(value = "code", required = false, defaultValue = "") code: String,
            @RequestParam(value = "error", required = false, defaultValue = "") error: String
    ): ModelAndView {
        if (!error.isNullOrEmpty()) {
            val model = ModelAndView("slack/error")

            model.status = HttpStatus.UNAUTHORIZED
            model.addObject("authorizeUri", slackService.authorizeUri())

            return model
        }

        if (!code.isNullOrEmpty()) {
            val accessToken: AccessToken? = slackService.requestAccessToken(code)

            return ModelAndView("slack/success")
                    .addObject("accessToken", accessToken)
        }

        return ModelAndView("slack/connect")
                .addObject("authorizeUri", slackService.authorizeUri())
    }

    @Operation(hidden = true)
    @RequestMapping(
            headers = ["${HttpHeaders.ACCEPT}=${MediaType.APPLICATION_JSON_VALUE}"],
            method = [RequestMethod.POST],
            produces = [MediaType.APPLICATION_JSON_VALUE],
            value = [Url.INTEGRATION_SLACK]
    )
    fun command(request: SlackCommandRequest): SlackCommandResponse {
        val command: String = if (request.text != null) {
            request.text!!.trim()
        } else {
            ""
        }

        if (Regex("help").matches(command)) {
            return SlackCommandHelpResponse()
        }

        if (command.startsWith("?")) {
            val pageMatcher: Matcher = Pattern.compile("--page\\s?(\\d+)").matcher(command)
            val pageNumber: Int =
                    if (pageMatcher.find())
                        if (pageMatcher.group(1).trim().toInt() - 1 <= 0) 0
                        else pageMatcher.group(1).trim().toInt() - 1
                    else 0

            val queryMatcher = Pattern.compile("/?\\s?([a-zA-Z0-9]+)").matcher(command)
            var query: String =
                    if (queryMatcher.find()) queryMatcher.group(0).trim()
                    else ""

            val result: Page<QuoteEntity> = quoteRepository.findByValueIgnoreCaseContaining(query, PageRequest.of(pageNumber, PAGE_SIZE))
            val cur: Pageable = result.pageable

            if (result.isEmpty) {
                return SlackCommandSearchResponse(
                        text = "Your search for *`${query}`* did not match any " +
                                "quote ¯\\_(ツ)_/¯. Make sure that all " +
                                "words are spelled correctly. Try different " +
                                "keywords. Try more general keywords.",
                        responseType = SlackResponseType.EPHEMERAL
                )
            }

            val attachments: List<SlackCommandResponseAttachment> = result
                    .toList()
                    .mapIndexed { index, quote: QuoteEntity ->
                        SlackCommandResponseAttachment(
                                fallback = quote.value,
                                text = quote.value,
                                title = "(${index + 1 + pageNumber * PAGE_SIZE})",
                                titleLink = "https://www.tronalddump.io/quote/${quote.quoteId}"
                        )
                    }.toList()

            val text = StringJoiner(" ")
            text.add("*Search results ${cur.pageNumber * PAGE_SIZE + result.numberOfElements} of ${result.totalElements}*.")

            if (result.hasNext()) {
                val next = result.nextPageable()
                text.add("Type `/${COMMAND} ? $query --page ${next.pageNumber + 1}` to see more results.")
            }

            return SlackCommandSearchResponse(
                    attachments = attachments,
                    text = text.toString()
            )
        }

        val entity = quoteRepository.randomQuote().orElseThrow {
            RuntimeException()
        }

        return SlackCommandQuoteResponse(
                attachments = listOf(
                        SlackCommandResponseAttachment(
                                fallback = entity.value,
                                mrkdownIn = listOf("text"),
                                text = entity.value,
                                title = "[permalink]",
                                titleLink = "https://www.tronalddump.io/quote/${entity.quoteId}"
                        ))
        )
    }
}