#import "SearchResultsTC.h"

@implementation SearchResultsTC

@synthesize titleLbl, salaryLbl, descLbl, redSignImage, delegate, jobId;

- (void) redButtonAction: (id) sender {
	[delegate redButtonAction: jobId];
}
- (void)dealloc {
    [super dealloc];
	[titleLbl release];
	[salaryLbl release];
	[descLbl release];
	[redSignImage release];
	[delegate release];
	[jobId release];
}

@end
