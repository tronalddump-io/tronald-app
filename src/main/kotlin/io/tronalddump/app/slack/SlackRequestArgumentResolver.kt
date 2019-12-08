package io.tronalddump.app.slack

import org.springframework.core.MethodParameter
import org.springframework.web.bind.support.WebDataBinderFactory
import org.springframework.web.context.request.NativeWebRequest
import org.springframework.web.method.support.HandlerMethodArgumentResolver
import org.springframework.web.method.support.ModelAndViewContainer

class SlackRequestArgumentResolver : HandlerMethodArgumentResolver {

    override fun resolveArgument(
            parameter: MethodParameter,
            mavContainer: ModelAndViewContainer?,
            webRequest: NativeWebRequest,
            binderFactory: WebDataBinderFactory?
    ): Any? {
        return SlackCommandRequest(
                channelId = webRequest.getParameter("channel_id"),
                channelName = webRequest.getParameter("channel_name"),
                command = webRequest.getParameter("command"),
                enterpriseId = webRequest.getParameter("enterprise_id"),
                enterpriseName = webRequest.getParameter("enterprise_name"),
                responseUrl = webRequest.getParameter("response_url"),
                teamDomain = webRequest.getParameter("team_domain"),
                teamId = webRequest.getParameter("team_id"),
                text = webRequest.getParameter("text"),
                token = webRequest.getParameter("token"),
                triggerId = webRequest.getParameter("trigger_id"),
                userId = webRequest.getParameter("user_id"),
                userName = webRequest.getParameter("user_name")
        )
    }

    override fun supportsParameter(parameter: MethodParameter): Boolean {
        return parameter.parameterType == SlackCommandRequest::class.java
    }
}