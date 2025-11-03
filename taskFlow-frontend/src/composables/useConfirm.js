import { getCurrentInstance } from 'vue'

export function useConfirm() {
  const instance = getCurrentInstance()
  const confirm = instance?.appContext.config.globalProperties.$confirm

  return {
    confirm: async (options = {}) => {
      return await confirm?.open(options)
    }
  }
}