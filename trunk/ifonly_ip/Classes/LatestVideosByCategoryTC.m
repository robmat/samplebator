#import "LatestVideosByCategoryTC.h"
#import "VideoCell.h"
#import "GData.h"

@implementation LatestVideosByCategoryTC

@synthesize dataArr;

- (void)viewDidLoad {
    [super viewDidLoad];
}

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return 1;
}


- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    return [dataArr count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    static NSString *CellIdentifier = @"VideoCell";
	GDataEntryYouTubeVideo* entry = [dataArr objectAtIndex:indexPath.row];
    VideoCell* cell = (VideoCell*) [tableView dequeueReusableCellWithIdentifier:CellIdentifier];
    if (cell == nil) {
		NSArray *topLevelObjects = [[NSBundle mainBundle] loadNibNamed:@"VideoCell" owner:self options:nil];
		for (id currentObject in topLevelObjects){
			if ([currentObject isKindOfClass:[UITableViewCell class]]){
				cell =  (VideoCell *) currentObject;
				break;
			}
		}
	}
	[cell initializeWithGData:entry];
    return cell;
}

- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	GDataEntryYouTubeVideo* entry = [dataArr objectAtIndex:indexPath.row];
	[[UIApplication sharedApplication] openURL:[NSURL URLWithString:[[entry content] sourceURI]]];
}

- (void)dealloc {
	[dataArr release];
    [super dealloc];
}

@end
