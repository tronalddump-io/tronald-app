package io.tronalddump.app

import io.tronalddump.app.tag.TagModelAssembler
import io.tronalddump.app.tag.TagRepository
import org.springframework.http.HttpHeaders
import org.springframework.http.MediaType
import org.springframework.stereotype.Controller
import org.springframework.web.bind.annotation.GetMapping
import org.springframework.web.bind.annotation.RequestMapping
import org.springframework.web.bind.annotation.RequestMethod
import org.springframework.web.servlet.ModelAndView

@Controller
@RequestMapping(value = [Url.INDEX])
class HomeController(
        val tagModelAssembler: TagModelAssembler,
        val tagRepository: TagRepository
) {

    @GetMapping("/")
    @RequestMapping(
            headers = [HttpHeaders.ACCEPT + "=" + MediaType.TEXT_HTML_VALUE],
            method = [RequestMethod.GET],
            produces = [MediaType.TEXT_HTML_VALUE],
            value = ["/"]
    )
    fun get(): ModelAndView {
        val tags = tagRepository.findAll()
        val tagModels = tagModelAssembler.toCollectionModel(tags)

        return ModelAndView("home")
                .addObject("tags", tagModels.content)
    }
}