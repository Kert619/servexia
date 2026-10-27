<script setup lang="ts">
import type { PaginationLastProps } from "reka-ui";
import type { HTMLAttributes } from "vue";
import type { ButtonVariants } from "@/components/ui/button";
import { ChevronsRight } from "@lucide/vue";
import { reactiveOmit } from "@vueuse/core";
import { PaginationLast, useForwardProps } from "reka-ui";
import { cn } from "@/lib/utils";
import { buttonVariants } from "@/components/ui/button";
import {
    TooltipProvider,
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from "@/components/ui/tooltip";

const props = withDefaults(
    defineProps<
        PaginationLastProps & {
            size?: ButtonVariants["size"];
            class?: HTMLAttributes["class"];
        }
    >(),
    {
        size: "default",
    },
);

const delegatedProps = reactiveOmit(props, "class", "size");
const forwarded = useForwardProps(delegatedProps);
</script>

<template>
    <TooltipProvider>
        <Tooltip>
            <TooltipTrigger as-child>
                <PaginationLast
                    data-slot="pagination-last"
                    :class="
                        cn(
                            buttonVariants({ variant: 'ghost', size }),
                            'gap-1 px-2.5 sm:pr-2.5',
                            props.class,
                        )
                    "
                    v-bind="forwarded"
                >
                    <slot>
                        <ChevronsRight />
                    </slot>
                </PaginationLast>
            </TooltipTrigger>
            <TooltipContent>Last page</TooltipContent>
        </Tooltip>
    </TooltipProvider>
</template>
