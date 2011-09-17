#import "SearchResultsTC.h"

@implementation SearchResultsTC

@synthesize titleLbl, salaryLbl, descLbl;

- (void)dealloc {
    [super dealloc];
	[titleLbl release];
	[salaryLbl release];
	[descLbl release];
}

@end
