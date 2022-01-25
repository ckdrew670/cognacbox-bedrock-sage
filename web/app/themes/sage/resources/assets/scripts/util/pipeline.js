

class Pipeline {
	constructor() {
		return new Promise(function(fulfil, reject) {
			try {
				fulfil();
			} catch (error) {
				reject(error);
			}
		});
	}
}

export default Pipeline;
