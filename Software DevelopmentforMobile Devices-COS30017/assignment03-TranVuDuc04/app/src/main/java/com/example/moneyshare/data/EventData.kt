package com.example.moneyshare.data

import java.io.Serializable

data class EventData(
    val eventName: String? = null,
    val eventDate: String? = null

) : Serializable
