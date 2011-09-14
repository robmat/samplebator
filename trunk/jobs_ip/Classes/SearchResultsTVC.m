#import "SearchResultsTVC.h"
#import "SearchResultsTC.h"
#import "CXMLNode.h"

@implementation SearchResultsTVC

@synthesize navCntrl, doc;

- (void)viewDidLoad {
    [super viewDidLoad];
}

- (NSInteger)numberOfSectionsInTableView:(UITableView *)tableView {
    return 1;
}

- (CGFloat)tableView:(UITableView *)tableView heightForRowAtIndexPath:(NSIndexPath *)indexPath {
	return 94;
}

- (NSInteger)tableView:(UITableView *)tableView numberOfRowsInSection:(NSInteger)section {
    NSArray* nodes = [doc nodesForXPath:@"/RunSimpleSearch/Job" error:nil];
	NSLog(@"%@", [doc description]);
	return [nodes count];
}

- (UITableViewCell *)tableView:(UITableView *)tableView cellForRowAtIndexPath:(NSIndexPath *)indexPath {
    SearchResultsTC *cell = nil;
    if (cell == nil) {
        NSArray *topLevelObjects = [[NSBundle mainBundle] loadNibNamed:@"SearchResultsTC" owner:self options:nil];
        for (id currentObject in topLevelObjects){
            if ([currentObject isKindOfClass:[UITableViewCell class]]){
                cell =  (SearchResultsTC *) currentObject;
                break;
            }
        }
    }
    CXMLNode* node = [[doc nodesForXPath:@"/RunSimpleSearch/Job" error:nil] objectAtIndex:indexPath.row];
	
	return cell;
}
- (void)tableView:(UITableView *)tableView willDisplayCell:(UITableViewCell *)cell forRowAtIndexPath:(NSIndexPath *)indexPath {

}
- (void)tableView:(UITableView *)tableView didSelectRowAtIndexPath:(NSIndexPath *)indexPath {
	
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
}
- (void)dealloc {
    [super dealloc];
	[navCntrl release];
	[doc release];
}

@end

