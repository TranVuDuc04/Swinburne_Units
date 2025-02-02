package com.example.musicrenting

import android.os.Parcel
import android.os.Parcelable

data class Instrument(
    val name: String,
    val color: String,
    var rating: Float,
    var isAvailable: Boolean,
    var pricePerMonth: Int,
    var quantity: Int,
    val type: String
) : Parcelable {
    constructor(parcel: Parcel) : this(
        parcel.readString() ?: "",
        parcel.readString() ?: "",
        parcel.readFloat(),
        parcel.readByte() != 0.toByte(),
        parcel.readInt(),
        parcel.readInt(),
        parcel.readString() ?: "" //read type from parcel
    )

    override fun writeToParcel(parcel: Parcel, flags: Int) {
        parcel.writeString(name)
        parcel.writeString(color)
        parcel.writeFloat(rating)
        parcel.writeByte(if (isAvailable) 1 else 0)
        parcel.writeInt(pricePerMonth)
        parcel.writeInt(quantity)
        parcel.writeString(type) //write type to parcel
    }

    override fun describeContents(): Int {
        return 0
    }

    companion object CREATOR : Parcelable.Creator<Instrument> {
        override fun createFromParcel(parcel: Parcel): Instrument {
            return Instrument(parcel)
        }

        override fun newArray(size: Int): Array<Instrument?> {
            return arrayOfNulls(size)
        }
    }
}
