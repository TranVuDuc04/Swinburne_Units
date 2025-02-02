package com.example.moneyshare.adapter

import android.content.Intent
import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.fragment.app.FragmentActivity
import androidx.recyclerview.widget.RecyclerView
import com.example.moneyshare.data.EventData
import com.example.moneyshare.activity.ParticipantActivity
import com.example.moneyshare.databinding.RcEventCardBinding
import com.example.moneyshare.activity.FeedbackFragment
import android.util.Log

class EventAdapter(
    private val events: List<EventData>,
    private val deleteEvent: (EventData) -> Unit // lambda for delete action
) : RecyclerView.Adapter<EventAdapter.EventViewHolder>() {

    class EventViewHolder(val binding: RcEventCardBinding) : RecyclerView.ViewHolder(binding.root)

    private val TAG = "EventAdapter"

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): EventViewHolder {
        val binding = RcEventCardBinding.inflate(LayoutInflater.from(parent.context), parent, false) // inflate view
        return EventViewHolder(binding)
    }

    override fun onBindViewHolder(holder: EventViewHolder, position: Int) {
        val event = events[position]
        holder.binding.tvCardName.text = event.eventName ?: "Event Name"
        holder.binding.tvCardDate.text = event.eventDate ?: "Event Date"

        holder.binding.btnDelete.setOnClickListener {
            deleteEvent(event) // trigger delete action
            Log.d(TAG, "Deleting event: ${event.eventName}")
            // check if context is FragmentActivity
            val context = holder.binding.root.context
            if (context is FragmentActivity) {
                val feedbackFragment = FeedbackFragment() // create feedback fragment
                feedbackFragment.show(context.supportFragmentManager, "FeedbackFragment") // show fragment
            }
        }

        holder.binding.root.setOnClickListener {
            val context = holder.binding.root.context
            val intent = Intent(context, ParticipantActivity::class.java).apply {
                putExtra("EVENT_NAME", event.eventName) // pass event name
            }
            context.startActivity(intent)
            Log.d(TAG, "Navigating to ParticipantActivity for event: ${event.eventName}")
        }
    }

    override fun getItemCount() = events.size
}
