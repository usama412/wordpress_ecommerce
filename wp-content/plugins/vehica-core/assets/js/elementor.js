"use strict"

function vehicaSetFieldTitle(fieldKey) {
    let name = 'Unknown Search Field'

    jQuery.each(window.McElementor.fields, (index, field) => {
        if (field.key === fieldKey) {
            name = field.name
        }
    })

    return name
}

function vehicaSetSortByTitle(sortByKey) {
    const sortBy = window.McElementor.sortByOptions.find((sortByOption) => {
        return sortByOption.key === sortByKey
    })

    if (typeof sortBy === 'undefined') {
        return 'Unknown Sort By'
    }

    return sortBy.name
}

function vehicaSetTopBarElementTitle(elementKey) {
    const element = window.McElementor.topBarElements.find((element) => {
        return element.key === elementKey
    })

    if (typeof element === 'undefined') {
        return 'Unknown Element'
    }

    return element.name
}